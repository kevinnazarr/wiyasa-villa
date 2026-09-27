# LOG-20260927-ci-locale-fix

## Task
Perbaiki perubahan lokal (locale routing) agar lolos verifikasi `ci.yaml` (jobs: backend, frontend, policy).

## Context / Assumptions
- User mengaktifkan ekstensi sqlite lokal (terkonfirmasi: `pdo_sqlite`, `sqlite3` tersedia; driver PDO: pgsql, sqlite).
- `phpunit.xml` memaksa test via sqlite `:memory:`; `ci.yml` job backend hanya install `pgsql, pdo_pgsql` (tanpa sqlite).
- Routing locale baru (`{locale?}` prefix + fallback) membuat `route(...)` tanpa param `locale` menghasilkan URL tanpa prefix, yang kena fallback 302/405.
- Fortify `redirects('logout', '/')` dan `EmailVerificationNotificationSentResponse::back()` menghasilkan URL tanpa locale.

## Changes
- `.github/workflows/ci.yml`: tambah `sqlite3, pdo_sqlite` ke `extensions:` job backend dan frontend (2 baris).
- `apps/web/app/Http/Middleware/SetLocale.php`: selalu fallback ke `DEFAULT_LOCALE` bila segmen bukan locale didukung; selalu `App::setLocale()` + `URL::defaults(['locale' => ...])`.
- `apps/web/app/Providers/AppServiceProvider.php`: `URL::defaults(['locale' => SetLocale::DEFAULT_LOCALE])` di `boot()` agar `route()` locale-aware di semua konteks (termasuk test).
- `apps/web/config/fortify.php`: `'home' => '/id/dashboard'` + `'redirects' => ['logout' => '/id']`.
- `apps/web/routes/settings.php`: redirect `settings` -> `/{locale}/settings/profile` locale-aware (tambah import `SetLocale`, `Request`).
- `apps/web/app/Http/Controllers/Settings/ProfileController.php`: `destroy()` redirect ke `/id` (bukan `/`).
- `apps/web/tests/Feature/Auth/VerificationNotificationTest.php`: tambah `->from(route('home'))` agar `back()` punya referer locale-aware.
- File dari task sebelumnya (tidak diubah di task ini, tapi bagian dari diff): `routes/web.php`, `bootstrap/app.php`, `HandleInertiaRequests.php`, `SetLocale.php` (baru), `LocaleRoutingTest.php` (baru).

## Business Rules Affected
- URL locale-scoped `/id` | `/en`; `id` default. Root `/` dan prefix tak dikenal redirect ke `/id/...`.
- Auth Fortify redirect (login/register/logout/verify) konsisten ke path ber-locale.
- Tidak ada perubahan aturan booking/pricing/payment.

## Tests
- `php artisan test --compact`: 46 passed, 169 assertions (sebelum fix: 32 passed / 14 failed; lalu 44/46; 45/46; final 46/46).
- `composer validate --strict`: valid.
- `php artisan migrate --force`: nothing to migrate.
- `php artisan about`: OK.
- `vendor/bin/pint --test` (10 file ubahan): passed.
- `npm run typecheck` (vue-tsc --noEmit): exit 0.
- `npm run build --workspace apps/web`: `built in 3.26s`, exit 0.
- Policy check manual (file wajib + layout): POLICY OK.

## Verification
- Replay 3 job CI secara lokal: backend (validate+migrate+Pest+about) lolos; frontend (typecheck+build) lolos; policy (file+layout checks) lolos.
- `route('home')` -> `http://localhost:8000/id`-aware via URL defaults; `route('dashboard', ['locale' => 'id'])` -> `/id/dashboard`.
- YAML ci.yml: `sqlite3, pdo_sqlite` hadir di kedua job (cek string, 2 kemunculan).

## Documentation
- Tidak ada perubahan PRD/ERD/FLOWCHART/ARCHITECTURE (perilaku sesuai dok locale yang ada).

## Memory Updates
Obsidian Vault: unavailable
Code-Base-Memory: unavailable

## Notes / Follow-ups
- `vendor/bin/pint --test` repo-wide masih gagal di `database/seeders/DatabaseSeeder.php` (pre-existing, `single_blank_line_at_eof`); ci.yml tidak menjalankan Pint sehingga tidak blokir CI. Jalankan `vendor/bin/pint --dirty` bila ingin bereskan.
- Step CI `npm run lint/typecheck --workspace apps/web --if-present` adalah no-op karena script tersebut tidak ada di `apps/web/package.json` (yang ada: `check`, `types:check`). Pertimbangkan selaraskan nama script agar lint/typecheck benar-benar jalan di CI.
- `LocaleRoutingTest` memakai `User::factory()->make()` (tidak persist); lolos karena route dashboard hanya butuh auth guard session, tapi konsisten lebih baik pakai `create()`.
