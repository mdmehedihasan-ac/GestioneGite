## Purpose
Short, actionable guidance for AI agents working on this repository (small PHP web app).

## Quick local run (how developers expect to run this)
- This is a plain PHP app intended to run under XAMPP/Apache + MySQL (MariaDB).
- Steps to start locally:
  1. Put the project folder inside XAMPP's `htdocs` (already under `c:/xampp/htdocs/...`).
  2. Start Apache and MySQL from the XAMPP control panel.
  3. Import the database dump `gestionegite (12).sql` into MySQL (creates `gestionegite` DB and tables).
  4. Open the site in a browser at `http://localhost/<path-to-folder>/index.php`.

## Big-picture architecture
- Procedural PHP, one page = one script (no framework). Pages include `nav.php` and `config.php` as the common wiring.
- Database access is via mysqli with prepared statements (see `login.php`, `register.php`).
- Authentication: `utente` table stores users; `IDTipo` distinguishes roles (1 = Docente, 2 = Commissione).
- Role-based routing/guards live in `nav.php` (it checks `$_SESSION['ruolo']` and redirects when needed).

## Key files & patterns (concrete examples)
- `config.php` — DB connection (host=localhost, user=root, empty password, DB `gestionegite`). Example: include this at top of pages to get `$conn`.
- `nav.php` — session bootstrap and access-control checks; use it for page-level guards. Example: pages call `include('nav.php')` then output content.
- `login.php` / `register.php` — use `mysqli_prepare` + `password_hash` / `password_verify`. Follow the same prepared-statement style when adding queries.
- `create_token.php` — example of interacting with an external API (portale.alittlejag.uk) and reading a cookie `user_token`. Be careful with CORS and secrets: tokens are stored in cookies here.
- `vetrina.js` — shared UI helpers (profile modal toggles, modal open/close). Keep DOM IDs / classes consistent with `nav.php` components.
- `gestionegite (12).sql` — canonical DB schema and seed rows (tables: `utente`, `tipoutente`, `statogita`, `gite5`, `gita1g`, `partecipanti`). Use as the authoritative schema.

## Project-specific conventions and cautions
- No Composer, no build step. Files are plain PHP + CSS + JS. Don't add framework assumptions.
- Procedural style: expect `include('nav.php')` and `include('config.php')` at the top of pages. Keep side effects minimal when changing these files.
- Always use prepared statements (the repo consistently uses `mysqli_prepare` + bind/execute). Copy the pattern from `login.php` and `register.php`.
- Role values are numeric: 1 = Docente, 2 = Commissione. Access controls depend on these values (`nav.php`).
- Passwords use PHP's `password_hash` and `password_verify` — maintain that when migrating or extending auth.

## Debugging & developer workflow notes
- PHP version used in SQL dump metadata: PHP 8.0.30 — assume PHP 8+. Test on same or newer version.
- When debugging locally:
  - Start Apache/MySQL in XAMPP. Import the SQL file if the DB is missing.
  - View PHP errors by enabling display_errors in php.ini (if needed) or check Apache/PHP logs in XAMPP.
  - Browser devtools for JS (`vetrina.js`) and network requests (external API in `create_token.php`).

## External integrations
- External token API: `https://portale.alittlejag.uk/api/` is referenced in `create_token.php` — tokens are read from `document.cookie` under `user_token`.

## Tests
- There are no automated tests in the repo. Rely on manual testing in a local XAMPP environment.

## Small examples to follow when editing or adding features
- DB query (pattern):
  - Prepare -> bind -> execute -> get_result or store_result -> fetch -> close statement. See `login.php` for a full example.
- Protect a page for Commissione-only:
  - include `nav.php` and early-return based on `$_SESSION['ruolo']` (see `nav.php` implementation).

## When creating a PR or making changes
- Explain DB changes and provide SQL migrations or updated dump snippets (the dump is the source of truth).
- If adding new JS APIs, reference existing cookie/token usage in `create_token.php` and add fallbacks when token is missing.

## Git / Push note
- This repo is a normal Git repo. From your machine run the usual sequence to commit and push. Example (PowerShell):
  - `git add .github/copilot-instructions.md`
  - `git commit -m "chore: add copilot instructions"`
  - `git remote add origin https://github.com/mdmehedihasan-ac/GestioneGite.git` (only if not set)
  - `git push -u origin main`

---
If any section is unclear or you want more examples (e.g., exact DB column references or example migrations), tell me which area to expand.
