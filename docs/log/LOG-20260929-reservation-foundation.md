# LOG-20260929-reservation-foundation

## Task
Two-phase task: (Phase 1) sync docs to actual state with FUTURE labels and no trim, then (Phase 2) reservation creation engine only with no HTTP wiring and no big refactor. Preserve customer flow Login/Register/2FA/Verify → Bookings → Detail → Profile (no customer dashboard), keep admin dashboard, keep locale-aware redirect to /id/bookings or /en/bookings, re-verify 61 Pest PASS + typecheck/build/lint baseline.

## Context / Assumptions
- Baseline HEAD 6011366 (develop, in sync with origin/develop). Clean tree at start.
- Actual state: migrations only users/cache/jobs/2FA; only User model; no app/Enums; no config/booking.php; Fortify Bookings* responses + ResolveLocale present; user pages bookings/index, bookings/show, profile/index only.
- Locked decisions: hold duration in config file; pricing snapshot flat columns; code WVS-XXXXXXXX unique retry; engine-only, no HTTP wiring; docs use FUTURE labels, no trim.
- `deep` category unavailable (no gpt-5.6-sol model); delegated Phase 2 to `unspecified-high` with laravel-best-practices + testing-best-practices skills.
- No cabin-row lock: cabins table is FUTURE, so overlap re-check inside DB transaction is the enforcement; lock documented for when cabins table lands.

## Changes
Phase 1 docs (FUTURE labels, content untouched):
- docs/PRD.md, docs/ERD.md, docs/ARCHITECTURE.md, docs/FLOWCHART.md — status headers annotated with actual state at HEAD 6011366 + FUTURE marking for reservation/payment/voucher/invoice/QR sections.

Phase 2 engine (new files, apps/web):
- config/booking.php — `default_hold_minutes` (env BOOKING_HOLD_MINUTES, int, min 1).
- app/Enums/ReservationStatus.php — PENDING_PAYMENT/CONFIRMED/EXPIRED/CANCELLED/CHECKED_IN/COMPLETED.
- app/Enums/ReservationSource.php — DIRECT_WEBSITE/ADMIN_MANUAL/OTHER.
- database/migrations/2026_09_29_000001_create_reservations_table.php — per spec; cabin_id indexed, NO fk (cabins FUTURE); composite index cabin/dates/status; CHECK checkout>checkin (non-sqlite drivers).
- app/Models/Reservation.php — fillable/casts, belongsTo User, active() + overlapping() scopes, overlaps() helper (check-in inclusive / check-out exclusive, whereDate for sqlite datetime-cast storage).
- app/Actions/Reservation/CreateReservationHold.php — single engine class, per-attempt txn, overlap re-check with ValidationException, WVS-XXXXXXXX retry ×5, hold_expires_at from config.
- tests/Feature/ReservationHoldTest.php — 8 Pest tests matching DashboardTest closure style.

No routes/controllers/middleware/Vue/TS/i18n edits. No cabins/payments/voucher/invoice tables. No Domain/Repositories/Services folders.

## Business Rules Affected
- Booking invariant enforced: no two active overlapping reservations on same cabin (existing_start < requested_end AND existing_end > requested_start).
- Active = CONFIRMED or CHECKED_IN or (PENDING_PAYMENT with hold_expires_at > now). Expired holds don't block. Adjacent stays allowed.
- Hold duration data-driven via config/booking.php (baseline 15 min). Booking codes unique WVS-XXXXXXXX.

## Tests
- New: ReservationHoldTest 8 tests (hold ok, overlap rejected, adjacent allowed, expired hold available, active hold blocks, sequential double-attempt single winner, booking_code unique retry, expired not counted active).
- `php artisan test --compact`: 69/69 PASS, 325 assertions (61 existing + 8 new).
- `php -l` ×7 new files: clean. `vendor/bin/pint --dirty`: passed.

## Verification
- Tests: 69 PASS (re-verified after delegation).
- Typecheck (`npm run typecheck`, vue-tsc): clean, no output errors.
- Build (`npm run build`): built in 3.22s (chunk-size warning only, pre-existing pattern).
- Lint (`npm run lint`, vp check): 82 files formatted, 76 files no warnings/errors.
- `git status`: 4 modified docs + 6 new paths (Actions/Reservation, Enums, Models/Reservation.php, config/booking.php, migration, ReservationHoldTest). No unrelated changes.
- Diagnostics: php/intelephense LSP not installed — php -l + tests + pint stand in (same as prior baseline note re Vue LSP declined).

## Documentation
Phase 1 doc sync done (see Changes). No other docs touched.

## Memory Updates
Obsidian Vault: updated (`Data-Model.md` — actual Phase 2 reservations state appended).
Code-Base-Memory: unavailable (no MCP tools in this session).

## Notes / Follow-ups
- Cabin-row pessimistic lock still open: add `lockForUpdate` on cabins table when it lands; overlap re-check is current enforcement.
- pgsql migration path (CHECK constraint branch) not executed — no pgsql in env; sqlite path verified.
- Next phases (out of scope): cabins table, voucher quota, payment webhook, invoice/QR, HTTP wiring of bookings pages to engine.
