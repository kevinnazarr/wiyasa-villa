# LOG-20260928-frontend-restructure

## Task
Refactoring struktur frontend Vue (Laravel + Inertia.js) berdasarkan area akses (public/user/admin/auth/settings) tanpa mengubah authentication, routing behavior, URL, atau business logic.

## Context / Assumptions
- Struktur awal masih pola starter kit: `pages/Welcome.vue`, `pages/Dashboard.vue`, `pages/auth/*`, `pages/settings/*`.
- Tidak ada component Navbar/Footer/LanguageSwitcher atau domain booking/cabin di codebase saat ini; `components/public/` kosong, `components/ui/` tetap untuk primitive UI.
- Tidak ada `.ai/rules`; tidak ada integrasi Obsidian Vault / Code-Base-Memory MCP di environment ini.
- URL locale `/id` dan `/en` tidak boleh berubah; hanya internal Inertia component path yang berubah.

## Changes
- `apps/web/resources/js/pages/Welcome.vue` → `apps/web/resources/js/pages/public/home/index.vue` (via `git mv`, isi tidak diubah).
- `apps/web/resources/js/pages/Dashboard.vue` → `apps/web/resources/js/pages/user/dashboard/index.vue` (via `git mv`, isi tidak diubah).
- `apps/web/resources/js/pages/admin/dashboard/index.vue`: baru, placeholder terpisah dari dashboard customer (menggunakan `PlaceholderPattern` + breadcrumb "Admin Dashboard").
- `apps/web/resources/js/layouts/public.vue`: baru, shell minimal public (slot + Toaster).
- `apps/web/resources/js/layouts/user.vue`: baru, wrapper `AppLayout` (sidebar) untuk area customer.
- `apps/web/resources/js/layouts/admin.vue`: baru, wrapper `AppLayout` (sidebar) untuk area admin.
- `apps/web/resources/js/app.ts`: layout resolver diperbarui — `public/home/index` → `PublicAreaLayout`, `user/*` → `UserAreaLayout`, `admin/*` → `AdminAreaLayout`, `auth/*` → `AuthLayout`, `settings/*` → `[AppLayout, SettingsLayout]`; case lama `Welcome` dihapus.
- `apps/web/routes/web.php`: `Route::inertia('/', 'Welcome')` → `'public/home/index'`; `Route::inertia('dashboard', 'Dashboard')` → `'user/dashboard/index'`; URL dan nama route tidak berubah.
- `apps/web/tests/Feature/LocaleRoutingTest.php`: ekspektasi component diperbarui ke `public/home/index` dan `user/dashboard/index`.
- `pages/auth/*` dan `pages/settings/*` dipertahankan pada posisinya (sudah sesuai target `pages/auth`, `pages/settings`); Fortify views (`auth/Login`, dll) dan controller settings tidak diubah karena path-nya tetap valid.

## Business Rules Affected
- Tidak ada perubahan business rule. Availability, pricing, voucher, hold, payment, dan authorization tidak disentuh.

## Tests
- `vendor/bin/pint --dirty --format agent`: passed.
- `php artisan test --compact tests/Feature/LocaleRoutingTest.php`: 6 passed, 39 assertions.
- `npm run typecheck` (`vue-tsc --noEmit`): passed, 0 errors.
- `npm run build` (`vp build`): sukses, `✓ built in 2.37s`.
- `grep` sisa referensi lama (`'Welcome'`, `'Dashboard'`, `component('Welcome`/`component('Dashboard`): hanya string judul/breadcrumb "Dashboard" di `AppHeader.vue`, `AppSidebar.vue`, `user/dashboard/index.vue` — bukan Inertia component path, aman.

## Verification
- `GET /` → redirect `/id`; `/id` dan `/en` me-render `public/home/index` dengan prop locale sesuai (via LocaleRoutingTest).
- `/en/dashboard` (auth) me-render `user/dashboard/index` dengan locale `en` (via LocaleRoutingTest).
- `php artisan route:list` menunjukkan route `dashboard` dan auth Fortify tetap terdaftar; tidak ada URL yang diubah.
- Tidak ada broken import: `Dashboard.vue` yang dipindah hanya mengimpor `@/components/PlaceholderPattern.vue` dan `@/routes` (keduanya tidak berubah).

## Documentation
- Tidak ada perubahan `docs/PRD.md`, `ERD.md`, `FLOWCHART.md`, `ARCHITECTURE.md`, `DESIGN.md` (refactoring struktur file, bukan business rule).

## Memory Updates
Obsidian Vault: unavailable
Code-Base-Memory: unavailable

## Notes / Follow-ups
- `components/public/`, `components/booking/`, `components/cabin/` belum diisi karena component tersebut belum ada di project; buat saat fitur Navbar/Footer/Layout/Public Pages dibangun.
- `layouts/user.vue` dan `layouts/admin.vue` saat ini sama-sama wrapper `AppLayout`; diferensiasi (menu/hak akses) dilakukan tahap berikutnya tanpa mengubah behavior auth.
- File `pages/admin/dashboard/index.vue` masih placeholder; belum ada route yang mengarah ke sana (disengaja — admin dashboard dipisah sebagai page, routing admin menyusul).
- Pre-existing dirty files tidak disentuh: `.gitignore`, `apps/web/resources/css/app.css`.

## Follow-up Fix — Blank `/id` (missing `resolve`)
- Gejala: `/id` blank di browser padahal server return 200 + component `public/home/index`.
- Root cause: `createInertiaApp` di `app.ts` tidak punya `resolve`, sehingga runtime Inertia (`resolve(name)` di `dist/index.js` line 724) melempar `resolve is not a function` dan Vue tidak pernah mount. Bug ini pre-dates restructure (versi asli commit 13ef5c6 juga tanpa `resolve`).
- Fix (`apps/web/resources/js/app.ts`): tambah `resolve` via `import.meta.glob<DefineComponent>('./pages/**/*.vue', { eager: true })` yang me-map `./pages/${name}.vue`; throw explicit `Unknown Inertia page` bila nama tidak dikenal. Tambah `import type { DefineComponent } from 'vue'`.
- Verifikasi: `npm run typecheck` 0 errors, Pint passed, Pest LocaleRoutingTest 6 passed 39 assertions, `npm run build` sukses 2.96s.
- Verifikasi browser (headless chromium-1243 `--dump-dom`): `/id` dan `/en` me-render `<h1>Welcome to Wiyasa Villa</h1>`; console bersih (hanya warning dbus/sqlite chromium, bukan JS error).
