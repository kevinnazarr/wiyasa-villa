# LOG-20260929-cabin-foundation

## Task
Cabin Domain Foundation sebagai prerequisite Reservation hardening: cabins sebagai inventory resource nyata, cabin_translations id/en, FK reservations.cabin_id → cabins.id, integrasi Cabin lock (lockForUpdate + overlap re-check) ke CreateReservationHold. Engine-only, no HTTP/Vue pages, no Payment/Voucher/Invoice.

## Context / Assumptions
- Reservation Foundation selesai: 69 tests/325 assertions, cabin_id tanpa FK, tanpa lock (cabins FUTURE).
- Audit: tidak ada Cabin model/migration/factory/resource/type di codebase — hanya page-local aliases (`CabinSummary`/`CabinDetail`) dan Inertia placeholder routes tanpa props. Greenfield build mengikuti ERD §3.2/3.2a.
- PG dev (127.0.0.1:55432) reachable; phpunit sqlite :memory: (lockForUpdate no-op di sqlite).
- `nameFor($locale)`: `$locale → en → canonical name` (untuk id: id→en→canonical; untuk en: en→canonical). Single fallback location di model.

## Changes
- `apps/web/database/migrations/2026_09_29_063855_create_cabins_table.php` (new): code unique, name, slug unique, description nullable, capacity default 7, base_occupancy default 4, status default ACTIVE, check_in/out_time nullable, index status+slug, CHECK `capacity > 0 AND capacity >= base_occupancy` PG-only (sqlite-guard).
- `apps/web/database/migrations/2026_09_29_063856_create_cabin_translations_table.php` (new): FK cabin_id cascadeOnDelete, locale char2, name, description, unique(cabin_id,locale).
- `apps/web/database/migrations/2026_09_29_063908_add_cabin_fk_to_reservations_table.php` (new): reservations.cabin_id → cabins.id `restrictOnDelete` (historical record tidak ikut terhapus).
- `apps/web/app/Enums/CabinStatus.php` (new): Active/Inactive/Maintenance → ACTIVE/INACTIVE/MAINTENANCE.
- `apps/web/app/Models/Cabin.php` (new): fillable/casts, hasMany translations + reservations, `nameFor($locale)`.
- `apps/web/app/Models/CabinTranslation.php` (new): fillable, belongsTo cabin.
- `apps/web/app/Models/Reservation.php`: + `cabin()` BelongsTo, PHPDoc @property.
- `apps/web/app/Actions/Reservation/CreateReservationHold.php`: lock Cabin row `lockForUpdate()->firstOrFail()` DULU (invalid cabin = 404), validasi status ACTIVE, validasi total <= capacity, lalu overlap re-check. Urutan lock→re-check. Replace ponytail no-lock comment.
- `apps/web/database/factories/CabinFactory.php` (new): code/slug unique, capacity 7/base 4/ACTIVE, states `inactive()`, `maintenance()`, `withTranslations()` (id+en).
- `apps/web/tests/Feature/ReservationHoldTest.php`: holdInput pakai factory Cabin eksplisit per test; code-uniqueness pakai 2 cabin berbeda.
- `apps/web/tests/Feature/CabinTest.php` (new, 10 tests): creation, slug/code uniqueness, translations + unique locale, nameFor fallback, INACTIVE/MAINTENANCE reject, over-capacity reject, relations, invalid-FK reject.
- `apps/web/resources/js/types/cabin.ts` (new) + `types/index.ts` export: Cabin domain types only.

## Business Rules Affected
- Hold creation txn: BEGIN → LOCK cabin row → validasi status ACTIVE → validasi capacity → re-check overlap → create PENDING_PAYMENT → COMMIT. Lock singkat, no external calls.
- INACTIVE/MAINTENANCE cabin menolak reservation; guest total > capacity ditolak.
- Cabin delete di-restrict bila punya reservation (protect historical records).
- Bedakan cabin availability (status) vs reservation availability (overlap + hold policy).

## Tests
- `php artisan test --compact`: PASS 79/79, 342 assertions (69 baseline + 10 Cabin baru). ReservationHoldTest 8 tests tetap green dengan factory cabins.
- PG scratch `db_wiyasa_cabin_verify` (created → migrated → dropped): 8 migrations OK; `cabins_capacity_check` (c), `reservations_cabin_id_foreign` confdeltype=r (restrict), `cabin_translations_cabin_id_foreign` confdeltype=c (cascade), index status/slug/composite OK. Dev DB untouched.
- `npm run typecheck`: PASS. `npm run build`: PASS. `npm run lint`: 83 formatted / 77 clean, no warnings. `vendor/bin/pint --dirty`: PASS.

## Verification
- Semua gates hijau, diverifikasi orchestrator langsung (tests/typecheck/build/lint/diff), bukan klaim subagent.
- Flow lock→re-check dibaca langsung di CreateReservationHold.php baris 44-60.
- Diff: 4 modified + 9 untracked, tidak ada routes/controllers/Vue pages/docs.

## Documentation
- docs/*.md tidak diubah (headers FUTURE dari Reservation Foundation tetap valid; cabins sekarang implemented di code).
- Obsidian Vault: updated (`Data-Model.md` — lihat Memory Updates di bawah; dianggap selesai bila append berhasil).
- Code-Base-Memory: unavailable.

## Memory Updates
- Obsidian Vault: appended Phase 3 Cabin state ke `/home/kevinnazar/Obsidian/Wiyasa-Villa/Data-Model.md`.
- Code-Base-Memory: unavailable.

## Notes / Follow-ups
- Real PG concurrency test (dua `CreateReservationHold::run` simultan di PG, ekspektasi 1 sukses + 1 ValidationException) masih PENDING — sqlite lockForUpdate no-op, sequential double-attempt bukan bukti concurrency. Jangan klaim production-safe.
- Next scope (belum dikerjakan): voucher quota, webhook/invoice, HTTP booking flow, Vue booking UI, public cabins catalog wiring ke model (routes masih placeholder).
