# Laravel Template

A ready-to-build-on Laravel 13 application: sign-in, users, groups & permissions,
settings, notifications, activity and sign-in logs, backups, an API, Arabic/English
with right-to-left support, and a generator that adds a full CRUD module in one command.

Start every new internal system from here and spend your time on the features that
are specific to it.

**[اقرأ بالعربية ← README.ar.md](README.ar.md)**

---

## What you get

| Area | Included |
|---|---|
| **Sign-in** | Email or username + password, "remember me", lockout after repeated failures, password reset by link **or** by authenticator code, optional self sign-up, Microsoft (Entra ID) single sign-on |
| **Two-factor** | Authenticator app (TOTP). Off / optional / required for everyone. Trusted browser for 30 days. Sign in with the code alone (passwordless). Reused codes are refused |
| **Users** | List, search, filters, create, edit, photo, deactivate, soft delete & restore, CSV export, welcome email, per-user permissions, see and sign out a person's devices |
| **Groups & permissions** | A permission tree (`config/permission_tree.php`) with allow / deny / inherit per group and per user, parent → child permissions, super user, protection against granting more than you have |
| **Dashboard** | Figures, a 14-day activity chart, who is online, recent activity |
| **Settings** | Pages generated from `config/settings.php`: general, branding (logo, colour), security, accounts, mail (with a test email), single sign-on, log retention, backups, system information |
| **Notifications** | Bell with live counter, notification page, site-wide announcements (scheduled, by type), "What's new" changelog |
| **Logs** | Activity log of every change (old → new value), sign-in log with new-device emails and lockout alerts, live presence ("online" vs "active") |
| **Backups** | Database + uploaded files, on a schedule set in Settings, download / delete / restore-ready zip, optional password |
| **API** | Personal access tokens (Laravel Sanctum) under `/api/v1`, a token can do only what its owner can |
| **Languages** | English and Arabic, full RTL layout, each person picks their own language and time zone |
| **Developer tools** | `make:module`, `lang:missing`, `permissions:check`, `app:create-admin`, 114 tests, GitHub Actions CI, Docker for local and production, Railway config |

Tech: Laravel 13 · PHP 8.3+ (8.5 in Docker) · MySQL 8.4 (PostgreSQL and SQLite also work) · Blade · Tailwind CSS 4 · Alpine.js · Chart.js · Font Awesome 7 · Vite 8.

---

## Quick start (Docker, recommended)

Needs only [Docker Desktop](https://www.docker.com/products/docker-desktop/).

```bash
git clone https://github.com/SalemBahlooli/Laravel-Template.git my-app
cd my-app
cp .env.example .env
docker compose up -d --build
docker compose exec app npm install
docker compose exec app npm run build
```

Open **http://localhost:8080** and sign in with `admin@example.com` / `password`
(set by `ADMIN_EMAIL` / `ADMIN_PASSWORD` in `.env`, only used when the database has no users).
**Change that password right away.**

| URL | What |
|---|---|
| http://localhost:8080 | the app |
| http://localhost:8025 | Mailpit: every email the app sends lands here |
| `localhost:33060` | MySQL (database, user `app`, password `secret`) for a database client |

Port already taken? Set `APP_PORT=8081` (or `MAILPIT_PORT`, `DB_FORWARD_PORT`, `VITE_PORT`) before `docker compose up`.

The container migrates the database, seeds it, and runs the scheduler and the queue worker for you.
For hot reload while editing views and CSS: `docker compose exec app npm run dev -- --host`.

Everyday commands:

```bash
docker compose exec app php artisan test           # the test suite
docker compose exec app php artisan migrate        # new migrations
docker compose exec app php artisan db:seed --class=DemoSeeder   # sample users and history
docker compose exec app vendor/bin/pint            # format the code
docker compose logs -f app                         # logs
```

## Quick start (without Docker)

PHP 8.3+ with `pdo_mysql` (or `pdo_sqlite`), `intl`, `gd`, `zip`, `bcmath`; Composer; Node 22.

```bash
composer setup        # install, .env, key, migrate + seed, build assets
composer dev          # server, queue worker, logs and Vite together
php artisan schedule:work   # in a second terminal: backups and log pruning
```

For SQLite set `DB_CONNECTION=sqlite` in `.env` and remove the other `DB_*` lines.

---

## Starting a new project from the template

1. On GitHub press **Use this template** (or clone and push to a new repository).
2. Follow the checklist in **[docs/getting-started.md](docs/getting-started.md)**: name, `composer.json`, colours, logo, languages, first admin.
3. Add your first feature:

   ```bash
   php artisan make:module Project --fields="name:string,budget:decimal:nullable,starts_on:date:nullable,is_done:boolean" --icon=fa-diagram-project
   php artisan migrate
   php artisan lang:missing ar --write    # then translate the new keys in lang/ar.json
   ```

   You now have a model, migration, factory, validated controller, list/form/details pages,
   routes, four permissions, a menu item, activity logging and a passing test.
4. Give a group the new permissions under **Groups & Permissions → Modules**.

## Documentation

| Guide | Read it when |
|---|---|
| [Getting started](docs/getting-started.md) | You start a new project from the template |
| [Architecture](docs/architecture.md) | You want to know where things are and why |
| [Modules](docs/modules.md) | You add a feature (with or without `make:module`) |
| [Permissions](docs/permissions.md) | You protect a page, button or API route |
| [Settings](docs/settings.md) | You need a value admins can change without a deploy |
| [Activity log & notifications](docs/activity-and-notifications.md) | You record what happened or tell someone about it |
| [Translations](docs/translations.md) | You add text, or a new language |
| [Deployment](docs/deployment.md) | You go to production (Railway, any Docker host, a VPS) |
| [Security](docs/security.md) | You review what is already protected and what is left to you |

Instructions also live **inside the code**: every config file under `config/` that you are
expected to edit (`permission_tree.php`, `settings.php`, `menu.php`) starts with a comment explaining
its format, and the main classes in `app/Support` describe how to use them.
[AGENTS.md](AGENTS.md) holds the rules for AI coding assistants.

## Tests and checks

```bash
php artisan test                 # 114 tests
php artisan lang:missing ar      # every English text has an Arabic translation
php artisan permissions:check    # every permission used in the code exists in the tree
vendor/bin/pint --test           # code style
```

All four run on every push in GitHub Actions (`.github/workflows/tests.yml`), plus a build of the production Docker image.

## License

MIT, see [LICENSE](LICENSE). This template was written from scratch. It takes no
code from Snipe-IT (AGPL), so projects built on it can use any license.
