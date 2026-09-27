# LOG-20260927-i18n-foundation

## Task

Implementasi fondasi i18n frontend (Vue 3 + vue-i18n) untuk Wiyasa Villa:
- Struktur file i18n frontend sesuai dokumentasi arsitektur (`ARCHITECTURE.md` Section 6).
- Inisialisasi instance `createI18n` dengan default locale `id` dan fallback `en`.
- Dictionary awal minimal (`id.ts` dan `en.ts`) berisi key umum/nyata (common, nav, booking status).
- Registrasi instance i18n pada entry point aplikasi (`apps/web/resources/js/app.ts`).
- Type-safe TypeScript tanpa `any`.
- Verifikasi via `types:check` dan `build`.

## Context / Assumptions

- Mengacu pada `ARCHITECTURE.md` Section 2.2.1, 6, dan `PRD.md` Section 10:
  - Locale baseline: `id` (default), `en` (fallback).
  - Struktur path: `resources/js/i18n/index.ts` dan `resources/js/i18n/locales/{id,en}.ts`.
- Scope murni fondasi infrastruktur frontend:
  - Tidak ada perubahan database/migrasi `users.locale`.
  - Tidak ada middleware backend/session/cookie locale resolution.
  - Tidak ada komponen language switcher UI.
  - Tidak ada translasi massal halaman eksisting.
- Dependensi `vue-i18n` (^11.4.12) sudah terpasang di `apps/web/package.json`.

## Changes

- `apps/web/resources/js/i18n/index.ts` (baru):
  - Inisialisasi instance `createI18n` dengan `legacy: false`.
  - Definisi konstanta `SUPPORTED_LOCALES = ['id', 'en'] as const`.
  - `DEFAULT_LOCALE = 'id'`, `FALLBACK_LOCALE = 'en'`.
  - Definisi type `MessageSchema = DeepStringRecord<typeof id>` untuk memastikan strict structure typing tanpa error literal narrowing antar locale.
- `apps/web/resources/js/i18n/locales/id.ts` (baru):
  - Dictionary terjemahan bahasa Indonesia untuk namespace `common`, `nav`, dan `booking`.
- `apps/web/resources/js/i18n/locales/en.ts` (baru):
  - Dictionary terjemahan bahasa Inggris yang simetris dengan `id.ts`.
- `apps/web/resources/js/app.ts`:
  - Mengimpor `i18n` dari `@/i18n`.
  - Mendaftarkan plugin `app.use(i18n)` pada hook `withApp` Inertia.

## Business Rules Affected

- Tidak ada logic booking atau transaksi yang diubah. Fondasi presentasi i18n siap digunakan sesuai spesifikasi `id` default dan `en` fallback.

## Tests

- `npm run types:check` (`vue-tsc --noEmit`) pada `apps/web`: PASS (exit code 0, 0 error).
- `npm run build` (`vp build`) pada `apps/web`: PASS (exit code 0, 3363 modules transformed, bundle berhasil di-generate).

## Verification

- TypeScript compilation clean: skema `MessageSchema` dan dictionary divalidasi tanpa compiler warnings/errors.
- Vite build verification: build production asset sukses tanpa error bundle atau chunk resolution.
- Scope audit: tidak ada file backend, migrasi, atau komponen yang diubah selain `app.ts` dan folder `i18n/`.

## Documentation

- `docs/log/LOG-20260927-i18n-foundation.md` dibuat.

## Memory Updates

- Obsidian Vault: not applicable
- Code-Base-Memory: not applicable

## Notes / Follow-ups

- Language switcher UI dapat diimplementasikan di navbar saat task terkait dijadwalkan.
- Backend locale synchronization (`users.locale` update & session handling) dapat dihubungkan saat integrasi backend-frontend dilakukan.
