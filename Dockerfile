# syntax=docker/dockerfile:1.7
#
# Image de l'application Madin' Jeunes Ambition.
#
# Une seule image sert les trois rôles de l'infrastructure : le serveur web
# (nginx + php-fpm), le worker de file d'attente et l'ordonnanceur. C'est le
# même code et les mêmes extensions dans les trois cas — un seul artefact à
# construire, à signer et à déployer, donc aucun risque de voir le worker
# tourner sur une version différente du site.
#
#   docker build -t mja:latest .
#   docker run --rm -p 8080:80 --env-file .env.docker mja:latest
#
# Le rôle se choisit par l'argument de lancement : web (défaut), queue,
# schedule, ou n'importe quelle commande à exécuter tel quel.

# ─────────────────────────────────────────────────────────────────────
# 1. Assets front — Vite + Tailwind
#    Sorti dans public/build, que l'image finale recopie.
# ─────────────────────────────────────────────────────────────────────
FROM node:22-alpine AS assets
WORKDIR /app

# Pas de package-lock.json dans le dépôt : `npm ci` échouerait.
COPY package.json vite.config.js ./
RUN npm install --no-audit --no-fund

# Le plugin de polices lit public/ et resources/ ; Tailwind scanne les vues.
COPY resources ./resources
COPY public ./public
RUN npm run build

# ─────────────────────────────────────────────────────────────────────
# 2. Dépendances PHP
#    L'installation se fait sur le seul couple composer.json/lock pour que
#    le cache de couche survive à toute modification du code applicatif.
# ─────────────────────────────────────────────────────────────────────
FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist \
        --no-scripts --no-autoloader

COPY . .
RUN composer dump-autoload --no-dev --optimize --classmap-authoritative

# ─────────────────────────────────────────────────────────────────────
# 3. Image applicative
# ─────────────────────────────────────────────────────────────────────
FROM php:8.3-fpm-alpine AS app

# ffmpeg : rendu des montages (mja:montages-video).
# gd compilé avec freetype/jpeg/webp : imagettftext() et les vignettes de
# partage en dépendent — un gd nu écrirait des images sans texte.
RUN set -eux; \
    apk add --no-cache \
        nginx supervisor ffmpeg tzdata icu-libs su-exec \
        libpng libjpeg-turbo freetype libwebp libzip; \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS libpng-dev libjpeg-turbo-dev freetype-dev libwebp-dev \
        libzip-dev icu-dev linux-headers; \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp; \
    docker-php-ext-install -j"$(nproc)" \
        gd zip intl pdo_mysql bcmath exif pcntl opcache; \
    pecl install redis; \
    docker-php-ext-enable redis; \
    apk del .build-deps; \
    rm -rf /tmp/pear /var/cache/apk/*

ENV TZ=America/Martinique \
    APP_ENV=production \
    APP_DEBUG=false

WORKDIR /var/www/html

COPY docker/php.ini            /usr/local/etc/php/conf.d/zz-mja.ini
COPY docker/php-fpm.conf       /usr/local/etc/php-fpm.d/zz-mja.conf
COPY docker/nginx.conf         /etc/nginx/nginx.conf
COPY docker/supervisord.conf   /etc/supervisord.conf
COPY docker/entrypoint.sh      /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

# Le code d'abord, les artefacts de build ensuite : ils écrasent ce que le
# dépôt ne contient pas (vendor/ et public/build sont ignorés par git).
COPY --chown=www-data:www-data . .
COPY --from=vendor --chown=www-data:www-data /app/vendor        ./vendor
COPY --from=assets --chown=www-data:www-data /app/public/build  ./public/build

# .env n'entre jamais dans l'image : la configuration vient de
# l'environnement du conteneur. Un .env recopié par mégarde prendrait le pas
# sur les variables de compose et ferait pointer la prod sur la base locale.
RUN set -eux; \
    rm -f .env .env.backup .env.production; \
    mkdir -p storage/framework/cache/data storage/framework/sessions \
             storage/framework/testing storage/framework/views \
             storage/app/public storage/logs bootstrap/cache; \
    chown -R www-data:www-data storage bootstrap/cache; \
    chmod -R u+rwX,g+rwX storage bootstrap/cache

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=40s --retries=3 \
    CMD php -r 'exit(@file_get_contents("http://127.0.0.1/up") === false ? 1 : 0);'

ENTRYPOINT ["entrypoint"]
CMD ["web"]
