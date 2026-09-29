# LOG-20260929-booking-flow

## Task
Booking Flow Foundation vertical slice: Cabin → Concurrency Hardening → Availability → HTTP Booking → Public Catalog → Booking UI → Customer Detail. Stop at PENDING_PAYMENT. Reuse same Reservation Engine everywhere, no duplicate logic, no big refactor.

## Context / Assumptions
- Baseline: Cabin real inventory, reservations.cabin_id FK restrict, engine LOCK CABIN→ACTIVE→CAPACITY→RECHECK→CREATE HOLD→COMMIT, 79 tests PASS; gap = no real PG concurrency test.
- PG dev live 127.0.0.1:55432; phpunit sqlite :memory: default.
- Scope excludes: payment/webhook/voucher/invoice/QR/pricing, dashboard return, auth/locale mechanism changes, admin manual booking.
- .ai/rules missing; vendor Fortify LSP noise pre-existing.

## Changes
- Phase 1: `tests/Feature/ReservationConcurrencyTest.php` — 2×pcntl_fork barrier-synced vs scratch `db_wiyasa_concurrency` (created→migrated→dropped), fresh PDO per child, asserts exactly 1 ok + 1 ValidationException + 1 row. Dev DB untouched (reservations=0, cabins=10 seed unchanged).
- Phase 2: `app/Actions/Reservation/CheckCabinAvailability.php` — advisory pre-check, AvailabilityResult available+reason (invalid_dates/invalid_cabin/unavailable_status/over_capacity/date_conflict). Engine NOT refactored (lock order preserved; split in docblock).
- Phase 3-4: `Public/CabinController` (ACTIVE-only list, slug→id, 404 inactive), `Public/BookingController` (preselect + advisory errors, store via engine only → booking.confirmation?code=), `User/BookingController` (owner-scoped, firstOrFail), `Requests/Booking/StoreBookingRequest`. routes/web.php same names/URLs, placeholders→controller-backed; new POST booking.store (auth). Wayfinder regenerated.
- Phase 5-8: 6 Vue pages wired (cabins list/detail+Book Now, booking Form bookingStore, confirmation code+status, user bookings list/detail). i18n reused, no new keys. Wayfinder only, locale preserved.
- Tests: `CabinAvailabilityTest` 9, `BookingFlowTest` 7, PublicPagesTest seed pine-ridge for slug detail.
- Docs: 4 headers synced to HEAD 453822e actual state (cabins/reservations/concurrency/availability/HTTP/UI implemented; payment/voucher/invoice/QR/pricing FUTURE).

## Business Rules Affected
- Hold invariant: exactly-one-hold under real PG row lock (proven, not assumed).
- Advisory pre-check vs authoritative engine kept separate deliberately.
- Confirmation lookup by booking_code is public-by-code (FUTURE: scope to owner/session).

## Tests
- `php artisan test --compact`: PASS 96/96, 443 assertions (79 baseline + 1 concurrency + 9 availability + 7 flow).
- Narrow: Concurrency 1/1, Availability 9/9, BookingFlow 7/7.
- `vendor/bin/pint --dirty`: PASS. `npm run typecheck`: PASS. `npm run build`: PASS 2.93s. `vp check`: 83 formatted / 77 clean (1 fix applied to cabins/show.vue).
- `php artisan route:list`: names preserved (cabins.index/show, booking.index/store/confirmation, bookings.index/show).

## Verification
- All gates run by orchestrator directly: tests/typecheck/build/lint/pint/route:list green.
- Controller files read directly (BookingController 86 lines, CabinController 53, User BookingController 42); no duplicate overlap logic (engine sole path).
- Diff: 8 modified + 7 untracked; no engine/config/auth/locale/dashboard changes.

## Documentation
- docs/ERD.md, PRD.md, ARCHITECTURE.md, FLOWCHART.md headers synced (bodies untouched).
- Obsidian Vault: updated (Data-Model.md Phase 4 entry).
- Code-Base-Memory: unavailable.

## Memory Updates
- Obsidian Vault: appended booking-flow state to Data-Model.md.
- Code-Base-Memory: unavailable.

## Notes / Follow-ups
- POST booking.store is auth-only (outside verified group) — confirm verified requirement intended for booking in later phase.
- Confirmation page public-by-code; scope later.
- Pricing engine absent (amounts pass-through 0 default); voucher/webhook/invoice/QR FUTURE.
- Files uncommitted per rules.
