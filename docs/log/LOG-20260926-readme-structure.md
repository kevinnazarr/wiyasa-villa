# LOG-20260926-readme-structure

## Task

Menulis ulang `README.md` agar mengikuti struktur contoh yang diberikan user (logo center → tagline center → badge center → Overview → Tech Stack → Project Structure → Getting Started → Documentation → Author → License → footer), dengan isi disesuaikan kondisi aktual repository — bukan template contoh.

## Context / Assumptions

- Contoh yang diberikan berasal dari project berbeda (`batik-nusantara`, Next.js + Laravel terpisah); hanya struktur/heading yang diikuti.
- Isi disusun dari kondisi nyata repo: manifest aktual (`apps/web/composer.json`, `apps/web/package.json`, root `package.json`), `docker-compose.yml`, isi folder `docs/`, `assets/`, `.github/`, dan `.env.example`.
- README lama menampilkan klaim yang tidak akurat terhadap kondisi saat ini: path `assets/brand/` (actual: file logo/favicon langsung di `assets/`), keberadaan `docs/memory/`, serta dependensi yang tidak terpasang (Pinia, TanStack Vue Query, Laravel Horizon — tidak ada di manifest). Ini dikoreksi agar sesuai kondisi aktual.
- `.env.example` default masih `DB_CONNECTION=sqlite`; koneksi PostgreSQL Docker (port 55432) didokumentasikan sebagai opsi dengan catatan, bukan klaim default.

## Changes

- `README.md` — ditulis ulang mengikuti struktur contoh:
  - Header center: logo `assets/wiyasa-villa-primary-logo.webp`, tagline, badge tech (Laravel, PHP, Inertia.js, Vue 3, TypeScript, Vite, Tailwind CSS, PostgreSQL, Redis, Docker, Status: In Development).
  - `## 📖 Overview` — deskripsi Wiyasa Villa + prinsip data-driven & booking consistency.
  - `## 🛠️ Tech Stack` — tabel badge per kelompok (Application, Frontend, Data, Development/Quality, External Services) berdasarkan manifest aktual.
  - `## 📁 Project Structure` — tree akurat repo (single app `apps/web`, `docs/log/`, `test/`, `infrastructure/docker/`, `docker-compose.yml`).
  - `## 🚀 Getting Started` — clone, `docker compose up -d`, tabel service/port, setup `apps/web`, workspace scripts root, tests via `composer test`.
  - `## 📚 Documentation` — tabel dokumen `docs/` aktual (PRD, ERD, FLOWCHART, ARCHITECTURE, DESIGN, log) + `AGENTS.md`.
  - `## 👤 Author`, `## 📄 License`, footer center "Dibuat oleh kevinnazarr".

## Business Rules Affected

- Tidak ada perubahan business rule; dokumentasi README saja.
- Deskripsi invariant booking (single active overlapping reservation) dipertahankan di Overview.

## Tests

- Tidak ada test yang dijalankan (perubahan dokumentasi murni, tidak menyentuh kode).

## Verification

- `README.md` dibaca ulang seluruhnya setelah penulisan — struktur heading sesuai contoh dan seluruh referensi path (`assets/wiyasa-villa-primary-logo.webp`, `docs/*.md`, `apps/web`, `AGENTS.md`) diverifikasi ada di repository.
- Port dan service pada tabel Getting Started dicocokkan dengan `docker-compose.yml` (Postgres 55432, Redis 56379, Mailpit 58025/58081).
- Tech stack dicocokkan dengan `apps/web/composer.json` dan `apps/web/package.json`.

## Documentation

- `README.md` diperbarui.
- Dokumen `docs/` lain tidak diubah.

## Memory Updates

- Obsidian Vault: not available
- Code-Base-Memory: not available

## Notes / Follow-ups

- `apps/` saat ini belum ter-track git (`git status` menampilkan `?? apps/`) — repo remote sudah ada (`kevinnazarr/wiyasa-villa`), commit/push dilakukan terpisah.
- Jika nanti dependensi Pinia / TanStack Vue Query / Horizon benar-benar ditambahkan, baris Tech Stack perlu diperbarui.

## Update — Tech Stack dirapikan

- `README.md` bagian `## 🛠️ Tech Stack` disusun ulang:
  - 5 tabel tanpa label diganti sub-bagian `###` berlabel: Application, Frontend, Data, Development & Quality, External Services.
  - Alignment kolom tabel diubah ke left-aligned konsisten (`| :--------- | :-------- |`).
  - Baris Midtrans disamakan format badge-nya (`logoColor=white`, tanpa logo simple-icons karena tidak tersedia).
  - Menambahkan baris Laravel Pint (dipakai di `composer lint`) ke kelompok Development & Quality.

