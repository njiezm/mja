# Déploiement par conteneurs

Deux fichiers à la racine décrivent tout : `Dockerfile` pour l'application,
`docker-compose.yml` pour l'infrastructure.

## Ce que contient l'image

Une seule image, `mja:latest`, construite en trois étapes :

1. **assets** — `node:22-alpine` compile les feuilles de style et le
   JavaScript avec Vite, et produit `public/build`.
2. **vendor** — `composer:2` installe les dépendances PHP de production et
   optimise l'autoloader.
3. **app** — `php:8.3-fpm-alpine` reçoit le code, `vendor/` et
   `public/build`, avec nginx, php-fpm et supervisor.

Extensions PHP compilées : `gd` (avec freetype, jpeg et webp, sans quoi
`imagettftext()` écrirait des images sans texte), `zip`, `intl`, `pdo_mysql`,
`bcmath`, `exif`, `pcntl`, `opcache`, `redis`. Le binaire **ffmpeg** est
présent : `php artisan mja:montages` en a besoin pour rendre les vidéos.

## Les quatre services applicatifs

La même image, trois rôles choisis par l'argument de lancement :

| Service     | Rôle       | Ce qu'il fait                                              |
|-------------|------------|------------------------------------------------------------|
| `app`       | `web`      | nginx + php-fpm, joue les migrations au démarrage          |
| `queue`     | `queue`    | `queue:work` — courriels de relance, traitements différés  |
| `scheduler` | `schedule` | `schedule:work` — purge, sauvegarde, relances quotidiennes |

Plus `db` (MySQL 8.4) et `redis` (cache, sessions, file d'attente).

Le service `scheduler` remplace le cron absent de l'hébergement mutualisé.
Les trois tâches déclarées dans `routes/console.php` tournent alors vraiment
à l'heure, et le middleware `DeclencheurRelances` n'a plus à s'en charger.

## Mise en route

```bash
cp .env.docker.example .env.docker
# renseigner DB_PASSWORD, DB_ROOT_PASSWORD, le SMTP, APP_URL

export COMPOSE_ENV_FILES=.env.docker     # PowerShell : $env:COMPOSE_ENV_FILES = '.env.docker'

docker compose run --rm --no-deps app php artisan key:generate --show
# reporter la clé obtenue dans APP_KEY de .env.docker

docker compose up -d --build
docker compose exec app php artisan mja:super-admin
```

Le site répond sur `http://localhost:8080` (`HTTP_PORT` pour changer de port).

`COMPOSE_ENV_FILES` n'est pas un détail : sans lui, compose ne lit
`.env.docker` que pour peupler les conteneurs, et les `${VARIABLES}` du
fichier compose — dont les mots de passe MySQL — restent vides. À défaut,
passer `--env-file .env.docker` à **chaque** commande.

## Exploitation

```bash
docker compose logs -f app                    # journaux du site
docker compose exec app php artisan tinker    # console
docker compose exec app php artisan mja:images-partage   # vignettes de partage
docker compose exec app php artisan mja:montages         # rendu des vidéos
docker compose exec app php artisan mja:backup           # sauvegarde immédiate

# récupérer les sauvegardes sur l'hôte
docker compose cp app:/var/www/html/storage/app/backups ./sauvegardes
```

## Ce qui est persisté

Un seul volume applicatif, `storage` : les fichiers déposés par les membres,
les sauvegardes, les journaux. Le code vient de l'image et n'est jamais monté
depuis l'hôte — faire tourner en production un dossier de travail, c'est
servir une version qui n'a été ni construite ni testée.

`public/images/` et `public/videos/` sont, eux, dans l'image : ce sont les
médias du site, versionnés avec le code. Une nouvelle photo suppose donc une
nouvelle construction d'image.

## Derrière un proxy HTTPS

`bootstrap/app.php` fait déjà confiance à `X-Forwarded-Proto`. Il suffit de
mettre `APP_URL=https://…` dans `.env.docker` et de faire pointer le proxy
(Traefik, Caddy, nginx de l'hôte) sur le port publié par `app`.

## Points de vigilance

- **`APP_KEY` ne change jamais.** Une nouvelle clé rend illisibles toutes les
  sessions et tous les cookies signés existants. L'entrypoint refuse de
  démarrer si elle est vide, plutôt que d'en fabriquer une différente à chaque
  redémarrage.
- **Les migrations ne tournent que dans le service `web`.** `queue` et
  `scheduler` démarrent avec `RUN_MIGRATIONS=false` : deux conteneurs migrant
  en parallèle se marcheraient dessus.
- **Les clés Stripe ne sont pas dans l'environnement.** Elles vivent dans la
  table `settings`, saisies depuis l'administration par un super-admin.
- **Les caches sont reconstruits à chaque démarrage** (`config:cache`,
  `route:cache`, `view:cache`). Mettre `CACHE_CONFIG=false` pour déboguer une
  configuration récalcitrante.
