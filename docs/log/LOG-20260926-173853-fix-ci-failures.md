# LOG-20260926-173853-fix-ci-failures

## Task

Memperbaiki kegagalan GitHub Actions CI pada job Backend (Laravel + Pest) dan Frontend (Vue + TypeScript + Vite) untuk push ke `main`, tanpa menonaktifkan CI.

## Context / Assumptions

- Run awal (`a5aee22`, run 36235042460) gagal dengan dua akar masalah yang dikonfirmasi dari log aktual (`gh run view --log-failed`), bukan asumsi:
  1. **Backend**: step `Create application key` gagal — `file_get_contents(.../apps/web/.env): Failed to open stream: No such file or directory`. Workflow tidak pernah membuat `.env` di workspace CI.
  2. **Frontend**: step `Build frontend` gagal — plugin `@laravel/vite-plugin-wayfinder` menjalankan `php artisan wayfinder:generate --with-form` saat `vp build`, tetapi `apps/web/vendor/autoload.php` tidak ada. Job frontend tidak memasang dependency PHP (job GitHub Actions tidak berbagi workspace).
- Setelah fix pertama, muncul kegagalan ketiga yang sebelumnya tertutup: **12 test gagal, 28 lulus**, seluruhnya `ViteManifestNotFoundException` (33 kemunculan, tidak ada error DB/Redis lain) — feature test me-render blade `app.blade.php` (`@vite`) tetapi backend job tidak menjalankan frontend build sehingga `public/build/manifest.json` tidak ada.
- Keputusan: **CI tidak dinonaktifkan** — perbaikan langsung di workflow/test agar status check tetap aktif melindungi branch. Catatan: `concurrency: cancel-in-progress: true` berarti push kedua membatalkan run pertama, jadi push dilakukan berurutan dengan menunggu hasil tiap run.

## Changes

- `.github/workflows/ci.yml`
  - Job **backend**: tambah step `Copy environment configuration` (`cp .env.example .env`) sebelum `Create application key`.
  - Job **frontend**: tambah `Setup PHP` (shivammathur/setup-php@v2, PHP 8.5, ekstensi sama dengan job backend), `Install PHP dependencies` (`composer install`), `Copy environment configuration`, dan `Create application key` sebelum step Node/build — sehingga `vendor/autoload.php` tersedia saat Wayfinder plugin memanggil artisan.
- `apps/web/tests/TestCase.php`
  - Tambah `setUp()` yang memanggil `$this->withoutVite()` (metode bawaan Laravel) — feature test menguji respons/props Inertia, bukan manifest asset Vite; manifest tidak boleh jadi prasyarat test di CI backend.

Commits (berurutan, masing-masing menunggu CI):
1. `130b262` — `ci: fix backend and frontend job failures`
2. `d3bb16a` — `test: disable Vite manifest requirement in feature tests`

## Business Rules Affected

- Tidak ada. Perubahan murni pipeline CI dan perilaku test; tidak ada perubahan kode produksi, booking, pricing, payment, atau konfigurasi bisnis.

## Tests

- `php artisan test` (lokal, reproduksi kondisi CI: `public/build` dipindah sementara sehingga manifest tidak ada, menggunakan PostgreSQL lokal dengan database test terpisah `wiyasa_ci_local` yang di-drop setelahnya, driver test sesuai `phpunit.xml`):
  - **40 passed (130 assertions), 0 failed** — cocok dengan 12 gagal + 28 lulus sebelum fix (40 total).
  - Catatan lokal: PHP di mesin dev ini tidak memiliki ekstensi `pdo_sqlite`, sehingga test tidak bisa dijalankan dengan SQLite `:memory:` secara lokal; digunakan PostgreSQL terpisah agar DB dev tidak tersentuh.
- `vendor/bin/pint --test tests/TestCase.php` → PASS.
- `composer validate --strict` → OK.
- `npx js-yaml .github/workflows/ci.yml` → YAML valid.
- Smoke test `php artisan wayfinder:generate --with-form` lokal → exit 0.

## Verification

- GitHub Actions run `36236342879` (head `d3bb16a`): **conclusion `success`**
  - ✓ Frontend • Vue + TypeScript + Vite (36s)
  - ✓ Backend • Laravel + Pest (39s)
  - ✓ Project Policy (4s)
  - Log kegagalan sebelumnya diverifikasi via `gh run view --log-failed` pada run 36235042460 dan 36236092279 untuk memastikan diagnosis tiap tahap.
- Run perantara `36236092279` (head `130b262`): Frontend ✓ lulus (fix pertama terbukti), Backend gagal hanya di `Run Pest` karena Vite manifest (memicu fix kedua).

## Documentation

- `docs/log/LOG-20260926-173853-fix-ci-failures.md` dibuat (file ini).

## Memory Updates

- Obsidian Vault: updated — note `Wiyasa-Villa/CI-Pitfalls.md` dibuat (pitfalls CI yang durable, tanpa kredensial).
- Code-Base-Memory: unavailable — MCP tidak tersedia di sesi ini.

## Notes / Follow-ups

- Anotasi non-blocking di CI: `ubuntu-latest` akan migrasi ke Ubuntu 26 mulai 19 Okt 2026 (informasi runner-images) — perlu dipantau, tidak mempengaruhi kegagalan ini.
- Dependabot membuka beberapa PR dengan branch sendiri; run CI di branch tersebut mengikuti workflow yang sama dan otomatis ikut teratasi setelah workflow ini di-merge ke `main` (PR Dependabot berbasis `main`).
- `apps/web/.env.example` dipakai apa adanya untuk CI; nilai env job (pgsql/redis) menimpa `.env` karena environment variable nyata memiliki prioritas atas `.env` di Laravel.
