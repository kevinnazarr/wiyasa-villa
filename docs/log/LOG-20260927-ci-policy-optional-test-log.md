# LOG-20260927-ci-policy-optional-test-log

## Task

Melonggarkan job `policy` pada workflow CI agar keberadaan direktori `test/` dan `docs/log/` tidak lagi menjadi syarat lolos CI.

## Context / Assumptions

- User menyebut `ci.yaml`; file aktual di repo adalah `.github/workflows/ci.yml` (satu-satunya workflow).
- Yang diubah hanya gate CI. Aturan agent `AGENTS.md` §12 (test di `test/`) dan §13 (log wajib untuk perubahan kode/konfigurasi) **tidak** diubah di task ini.
- `test/` dan `docs/log/` tetap ada di repository saat ini; yang dihapus adalah kewajibannya di CI sehingga struktur repo boleh berubah tanpa membuat CI merah.
- Tidak ada perintah CI lain yang memeriksa `docs/log` atau root `test/` (job `backend` memakai `apps/web/tests` via `php artisan test`).

## Changes

- `.github/workflows/ci.yml` → job `policy` → step `Required project files`: menghapus dua baris pemeriksaan:
  - `test -d docs/log`
  - `test -d test`

  Keduanya dihapus tanpa pengganti; tidak ada pemeriksaan lain di CI yang mengacu ke `test/` atau `docs/log/`. Pemeriksaan `test -f` untuk file wajib (AGENTS.md, package.json, package-lock.json, apps/web/composer.json, docs/PRD.md, docs/ERD.md, docs/FLOWCHART.md, docs/ARCHITECTURE.md) dan step `Repository layout sanity check` tidak diubah.

## Business Rules Affected

Tidak ada. Tidak menyentuh aturan booking, pricing, payment, authorization, atau skema data.

## Tests

- Parse YAML: `Symfony\Component\Yaml\Yaml::parseFile('.github/workflows/ci.yml')` → OK (tidak ada error).
- Uji perilaku (blok step dijalankan di tree tracked dari `git archive HEAD` yang sudah dihapus `test/` dan `docs/log/`):
  - blok step versi baru (working tree) → `exit 0` → PASS.
  - blok step versi lama (HEAD) → exit bukan 0 → FAIL (membuktikan pemeriksaan itulah penghalangnya, bukan faktor lain).

## Verification

```bash
# YAML valid
php -r "require 'apps/web/vendor/autoload.php'; Symfony\Component\Yaml\Yaml::parseFile('.github/workflows/ci.yml');"

# tree uji tanpa test/ dan docs/log/
git archive HEAD | tar -x -C /tmp/ci-policy-check
rm -rf /tmp/ci-policy-check/test /tmp/ci-policy-check/docs/log
cd /tmp/ci-policy-check && bash /tmp/policy-new.sh   # exit 0
cd /tmp/ci-policy-check && bash /tmp/policy-old.sh   # exit != 0
```

Hasil: versi baru lolos tanpa `test/` & `docs/log/`; versi lama gagal, sehingga perubahan ini benar-benar melonggarkan gate tersebut.

## Documentation

- Hanya log ini. `AGENTS.md` dan dokumentasi `docs/` tidak diubah.

## Memory Updates

Obsidian Vault: updated (`Decisions/2026-09-27-ci-policy-tidak-mewajibkan-test-dan-docs-log.md`)
Code-Base-Memory: unavailable

## Notes / Follow-ups

- CI hanya memeriksa keberadaan direktori, bukan isinya. Jika ke depan ingin CI benar-benar mengabaikan keberadaan test/log, tidak ada langkah lain yang perlu diubah.
- Perubahan ini masih **uncommitted** di working tree; belum di-commit maupun di-push.
- Working tree masih memuat 11 file i18n/routing yang staged dari task sebelumnya; jangan digabung dalam satu commit dengan perubahan CI ini tanpa sengaja.
