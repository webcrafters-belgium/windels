# Repository Guidelines

## Project Structure & Module Organization
- `index.php` is the main entry point; `header.php` and `footer.php` provide shared layout.
- `pages/` holds public pages; `admin/` contains admin screens; `API/` contains API endpoints.
- `partials/` and `templates/` store reusable view fragments.
- `classes/` and `functions/` hold PHP helpers and business logic.
- `css/`, `js/`, `assets/`, `images/`, `fonts/`, and `styles/` hold static assets.
- `config/`, `ini.inc`, and `.env` are configuration sources; `logs/` is for runtime logs.
- `dev/` contains local utilities and test pages; `lib/` is third-party code; `localhost.sql` is a local DB dump.

## Build, Test, and Development Commands
- `composer install` installs PHP dependencies defined in `composer.json`.
- Run locally via Apache/Nginx with the document root set to this `htdocs` folder.
- Quick local server: `php -S localhost:8000 -t .` (note: `.htaccess` rules will not apply).
- No JS/CSS build pipeline is configured; assets are served directly.

## Coding Style & Naming Conventions
- PHP uses 4-space indentation with braces on the same line.
- Short echo tags (`<?= ?>`) are used; keep output escaped via existing helpers.
- Prefer lowercase names with dashes/underscores to match current paths.
- No formatter or linter is configured; keep changes consistent with nearby code.

## Testing Guidelines
- No automated test runner is detected in this repository.
- Manual checks live under `dev/`, `API/test/`, and `pages/test/`.
- Vendor tests under `lib/diff-match-patch-master/**/tests` are third-party only.

## Commit & Pull Request Guidelines
- No `.git` directory is present here, so commit conventions cannot be inferred.
- If needed, use concise, imperative subjects (example: `fix: handle empty cart`).
- PRs should include a summary, linked issues, screenshots for UI changes, and any config/DB notes.

## Agent-Specific Instructions
- Exclude `lib/` from repository-wide searches (example: `rg --glob '!lib/**' --files`).

## Security & Configuration Tips
- Treat `ini.inc`, `.env`, and `config/credentials.json` as sensitive; avoid committing secrets.
- `localhost.sql` is intended for local development; import into a local database that matches your credentials.

## GOALS
- We are overhauling this complete project into a modernised, sleek project. If Laravel is needed, implement it. 