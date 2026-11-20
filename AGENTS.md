# Guide for AI coding assistants

This is a Laravel 13 application built from **Laravel Template**. Read this before changing code.
Full guides are in `docs/`; the README explains setup.

## Running things

PHP may not be installed on the host. Everything runs in Docker:

```bash
docker compose up -d                                   # app :8080, MySQL :33060, Mailpit :8025
docker compose exec -T app php artisan test --compact  # tests (SQLite in memory)
docker compose exec -T app vendor/bin/pint             # format PHP
docker compose exec -T app php artisan lang:missing ar
docker compose exec -T app php artisan permissions:check
docker compose exec -T app npm run build
```

Without Docker, drop the `docker compose exec -T app` prefix. In Docker, `vendor/` and `node_modules/` are volumes: run composer and npm inside the container.

## Before you finish any change

1. `php artisan test` passes.
2. `vendor/bin/pint --test` passes.
3. `php artisan lang:missing ar` reports nothing missing. New English text has an Arabic translation in `lang/ar.json`.
4. `php artisan permissions:check` passes.

CI runs the same four checks.

## Conventions

- **New feature with a list, form and details page:** `php artisan make:module Name --fields="..."`, then adjust the generated code.
  Do not hand-write what the generator produces.
- **Permissions:** declare every permission in `config/permission_tree.php` (dotted names: `invoices.approve`).
  Check permissions with `can:` route middleware, `HasMiddleware` in controllers, `@can` in Blade or `$user->hasAccess()`.
  Never compare group names or check `isSuperUser()` for feature access.
- **Settings an admin may change:** declare them in `config/settings.php` and read them with `setting('key')`. Do not add `.env` variables for them.
- **Routes:** module routes go in `routes/modules/<name>.php`, which is loaded inside the signed-in group. Keep `routes/web.php` for the core.
- **Menu:** `config/menu.php` with `'can' => '<permission>'`.
- **Logging:** add `use LogsActivity;` to models. Use `Activity::log()` for actions that are not a plain create/update/delete.
- **Notifications:** `App\Notifications\SimpleNotification`.
- **Text:** write English inside `__()`. Never hard-code Arabic in views; it goes in `lang/ar.json`.
- **RTL:** use logical Tailwind classes (`ms-`, `me-`, `ps-`, `pe-`, `start-`, `end-`, `text-start`). Do not use `ml-`, `mr-`, `left-` or `right-` for layout.
- **Views:** use the components in `resources/views/components` (`x-layouts.app`, `x-card`, `x-input`, `x-select`, `x-toggle`, `x-textarea`, `x-badge`, `x-empty`, `x-delete-button`, `x-modal`).
- **Dates:** stored in UTC. Display with `format_date()` / `format_datetime()`.
- **Tests:** feature tests with `RefreshDatabase`. `$this->signInWith(['perm.name'])` signs in someone with exactly those permissions.
  Every new route gets a test for the forbidden case.
- **Validation:** Form Requests for anything with more than a couple of fields.

## Do not remove

- The marker comments `// @module-permissions` (in `config/permission_tree.php`) and `// @module-menu` (in `config/menu.php`). `make:module` inserts above them.
- The `superuser` permission and the seeded Administrators group.
- The middleware in `bootstrap/app.php` (locale, active account, two-factor, presence, catch-all logging, security headers).
- `Activity::SECRET` entries: those columns must never be logged.
