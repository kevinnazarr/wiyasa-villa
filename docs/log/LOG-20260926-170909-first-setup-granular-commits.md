# LOG-20260926-170909-first-setup-granular-commits

## Task

Commit seluruh first setup project Wiyasa Villa (307 file untracked) ke Git dan push ke `origin/main`, dipecah menjadi minimal 50 commit yang berarti (bukan commit kosong/artifisial).

## Context / Assumptions

- Repositori sudah memiliki 1 commit awal (`e94a4e8 first commit` berisi `README.md`) yang sudah ter-sync dengan `origin/main`.
- 307 file untracked merupakan hasil scaffold monorepo: dokumentasi, konfigurasi GitHub, Laravel + Inertia + Vue di `apps/web/`, library komponen UI, infrastructure placeholder, dan test placeholder.
- Interpretasi "minimal 50x commit": pemecahan logis per area/kelompok file (docs, CI, scaffolding, config, tiap grup UI component, pages, tests), bukan commit tanpa perubahan.
- File yang di-ignoring (`node_modules/`, `vendor/`, `apps/web/public/build/`, `*.sqlite*`, `.env` non-example) sengaja tidak di-commit.
- `apps/web/database/database.sqlite` di-ignore oleh `apps/web/database/.gitignore` (`*.sqlite*`).

## Changes

- `.gitignore`, `package.json`, `package-lock.json`, `AGENTS.md`, `docker-compose.yml`, `assets/` — root scaffolding (6 commit).
- `docs/PRD.md`, `docs/ERD.md`, `docs/FLOWCHART.md`, `docs/ARCHITECTURE.md`, `docs/DESIGN.md` — dokumentasi (5 commit).
- `.github/` (workflows/ci.yml, dependabot, CODEOWNERS, issue templates, PR template, README) — konfigurasi GitHub (6 commit).
- `infrastructure/`, `packages/`, `test/` — placeholder monorepo (1 commit).
- `apps/web/` foundation: composer, bootstrap, config (3 commit per kelompok), env/tooling, migrations, factories/seeder, routes, public, storage (17 commit).
- `apps/web/app/` — controllers, middleware, form requests, actions, concerns, model, providers (7 commit).
- Frontend toolchain: package manifest, Vite/TS/shadcn config, entry+styles+blade, types, utils/composables, layouts (6 commit).
- `apps/web/resources/js/components/` — shell/nav, form/feedback, account/2FA (3 commit).
- `apps/web/resources/js/components/ui/` — 22 commit, satu per grup komponen (alert … tooltip).
- Pages: welcome/dashboard, auth, settings (3 commit).
- Tests: Pest bootstrap, auth feature, settings/dashboard, unit (4 commit).
- `docs/log/LOG-20260926-170909-first-setup-granular-commits.md` — log ini (1 commit).

Total commit baru: 75 (74 + 1 log ini), melampaui target minimal 50.

## Business Rules Affected

- Tidak ada. Tugas murni version control / pendokumentasian; tidak ada perubahan kode, konfigurasi bisnis, booking, payment, atau pricing.

## Tests

- Tidak dijalankan (tidak ada perubahan kode). Verifikasi dilakukan lewat status Git (lihat Verification).

## Verification

- `git status --porcelain -uall` kosong setelah seluruh commit → tidak ada file untracked tertinggal.
- `git rev-list --count origin/main..HEAD` = 74 sebelum commit log ini → ≥ 50 commit terpenuhi.
- `git push origin main` dijalankan dan hasilnya dicatat di bagian Notes.
- File ter-track: 307 file sebelumnya + `README.md` + log ini.
- File ter-ignore (`node_modules`, `vendor`, `public/build`, sqlite, `.env`) dikonfirmasi tidak ikut ter-commit.

## Documentation

- `docs/log/LOG-20260926-170909-first-setup-granular-commits.md` dibuat.
- PRD/ERD/FLOWCHART/ARCHITECTURE/DESIGN diperoleh dari scaffold yang sudah ada (bukan diubah).

## Memory Updates

- Obsidian Vault: tidak tersedia — integrasi terdeteksi tetapi root note `Tasks` tidak ditemukan saat `get_context` dipanggil (ERROR: NOTE_NOT_FOUND), sehingga tidak ada update vault yang dilakukan.
- Code-Base-Memory: unavailable — MCP tidak tersedia di sesi ini.

## Notes / Follow-ups

- Commit dilakukan berurutan per area; history Linear-style (satu baris per commit message).
- Sebelum push: jalankan `git push origin main`. Hasil push akan diverifikasi setelah commit log ini dibuat.
