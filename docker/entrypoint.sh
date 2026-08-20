#!/bin/sh
# Point d'entrée de l'image MJA.
#
# Trois rôles pour une seule image : web, queue, schedule. Tout ce qui doit
# être vrai avant de servir la première requête (base joignable, caches à
# jour, lien de stockage) se fait ici, pas dans le Dockerfile : la
# configuration n'existe qu'au lancement du conteneur.
set -e

role="${1:-web}"

log() { echo "[entrypoint] $*"; }

# ── Clé d'application ───────────────────────────────────────────────
# Sans APP_KEY, Laravel refuse de démarrer et les sessions chiffrées sont
# illisibles. On ne la génère pas à la volée : une clé différente à chaque
# redémarrage invaliderait toutes les sessions et tous les cookies signés.
if [ -z "$APP_KEY" ]; then
    log "ERREUR : APP_KEY est vide."
    log "Générez-la une fois pour toutes :  docker compose run --rm app php artisan key:generate --show"
    log "puis reportez-la dans .env.docker."
    exit 1
fi

# ── Attente de la base de données ───────────────────────────────────
# Le `depends_on` de compose n'attend que le démarrage du conteneur, pas que
# MySQL accepte les connexions. Sans cette boucle, la première migration
# échoue une fois sur deux au démarrage à froid.
if [ "${DB_CONNECTION:-mysql}" = "mysql" ] && [ -n "${DB_HOST:-}" ]; then
    log "attente de ${DB_HOST}:${DB_PORT:-3306}…"
    i=0
    until php -r '
        $h = getenv("DB_HOST"); $p = getenv("DB_PORT") ?: 3306;
        exit(@fsockopen($h, (int) $p, $e, $s, 2) ? 0 : 1);
    '; do
        i=$((i + 1))
        [ "$i" -ge 60 ] && { log "base injoignable après 60 tentatives"; exit 1; }
        sleep 2
    done
    log "base joignable."
fi

# ── Répertoires inscriptibles ───────────────────────────────────────
# storage est monté en volume : les droits du volume ne sont pas ceux de
# l'image, il faut les reposer à chaque démarrage.
mkdir -p storage/framework/cache/data storage/framework/sessions \
         storage/framework/views storage/app/public storage/logs \
         storage/app/backups bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Tout artisan tourne sous www-data : lancé en root, il déposerait des
# caches que php-fpm — qui, lui, est www-data — ne pourrait plus réécrire.
art() { su-exec www-data php artisan "$@"; }

# Lien public/storage → storage/app/public, pour les fichiers déposés.
if [ ! -e public/storage ]; then
    art storage:link --quiet || log "storage:link a échoué (sans gravité si déjà en place)"
fi

# ── Migrations ──────────────────────────────────────────────────────
# Déclenchées par un seul rôle : deux conteneurs migrant en parallèle se
# marcheraient dessus. Le web est celui qui démarre en premier.
if [ "$role" = "web" ] && [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    log "migrations…"
    art migrate --force --no-interaction
fi

# ── Caches ──────────────────────────────────────────────────────────
# On vide avant de reconstruire : un cache hérité d'une image précédente
# porterait l'ancienne configuration.
art optimize:clear --quiet || true
if [ "${CACHE_CONFIG:-true}" = "true" ]; then
    art config:cache --quiet
    art route:cache --quiet
    art view:cache --quiet
fi

case "$role" in
    web)
        log "démarrage web (nginx + php-fpm)"
        exec supervisord -c /etc/supervisord.conf
        ;;
    queue)
        log "démarrage du worker de file d'attente"
        exec su-exec www-data php artisan queue:work \
            --sleep=3 --tries=3 --max-time=3600 --timeout=300
        ;;
    schedule)
        log "démarrage de l'ordonnanceur"
        exec su-exec www-data php artisan schedule:work
        ;;
    *)
        # Tout le reste est exécuté tel quel : artisan, composer, un shell…
        exec "$@"
        ;;
esac
