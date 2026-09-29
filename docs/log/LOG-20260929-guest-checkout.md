# LOG-20260929-guest-checkout

## Task
Guest Checkout Foundation + Secure Confirmation Foundation on existing booking flow. No reservation engine rewrite, no stable architecture change. Flow: Cabin → Date+Guests → Availability → Guest Information → CreateReservationHold → PENDING_PAYMENT → Secure Confirmation via public_token (?token=). Out of scope: payment/pricing/voucher/invoice/refund/cancel/claim/auto-account/email.

## Context / Assumptions
- Engine guarantees preserved: lock cabin row → recheck overlap/blocks → validate capacity → price → create hold → commit. Webhook/idempotency untouched.
- POST booking.store made public (auth middleware removed); /bookings index/show stay under auth+verified with owner scoping.
- booking_code WVS-XXXXXXXX remains non-secret display identifier; public_token (Str::random(32)) gates confirmation access.
- Authenticated user_id taken from auth, never from request; guest fields fall back to auth user name/email on store.
- Tests run on sqlite :memory: (phpunit.xml default); migration 2026_09_29_081607 verified via migrate path in tests, not run against dev PG.

## Changes
- `apps/web/database/migrations/2026_09_29_081607_add_guest_checkout_to_reservations_table.php` (new): drops user FK, re-adds user_id nullable nullOnDelete; adds guest_name/guest_email/guest_phone nullable; adds public_token nullable unique. Down reverses all.
- `apps/web/app/Models/Reservation.php`: user_id ?int, fillable + guest_name/guest_email/guest_phone/public_token. Scopes active/overlapping unchanged.
- `apps/web/app/Actions/Reservation/CreateReservationHold.php`: user_id ?? null, guest_* snapshot passthrough, public_token via generateToken() (Str::random(32)). Lock/Active/capacity/overlap checks, 5-attempt retry, 15min hold unchanged.
- `apps/web/app/Http/Requests/Booking/StoreBookingRequest.php`: guest_name/guest_email Rule::requiredIf(auth()->guest()), guest_phone nullable. Base cabin/date/guest rules unchanged.
- `apps/web/app/Http/Controllers/Public/BookingController.php`: index passes authUser {name,email}|null; store uses $user?->id + guest fallback to auth user, locale from route, redirects booking.confirmation with token=public_token; confirmation looks up by public_token ?token=.
- `apps/web/routes/web.php`: POST booking public (no auth middleware). /bookings under auth+verified unchanged.
- `apps/web/resources/js/pages/public/booking/index.vue`: guest fieldset guest_name/email/phone with :value prefill from authUser + InputError.
- `apps/web/resources/js/i18n/locales/id.ts`, `en.ts`: guestHeading/guestName/guestEmail/guestPhone keys; added booking.children (Anak/Children), booking.infants (Bayi/Infants).
- `apps/web/resources/js/pages/public/booking/index.vue`: cabin_id input gained min=1; children/infants labels now use $t keys (were hardcoded English).
- `apps/web/tests/Feature/BookingFlowTest.php`: redirect asserts token=public_token, confirmation via ?token=; new guest hold test (no login, user_id null, snapshot, token redirect), token-gating test (code alone → null, valid token → code+PENDING_PAYMENT, wrong token → null), /bookings owner redirects preserved + POST with empty payload asserts session errors.

## Business Rules Affected
- user_id nullable: guest holds have user_id null + guest snapshot; auth holds carry auth id + snapshot prefill.
- Confirmation access gated by unguessable public_token, not booking_code.
- /bookings ownership preserved (auth + user_id scoping).
- Hold/overlap/concurrency rules untouched; engine remains sole reservation path.

## Tests
- Focused BookingFlowTest: 9 passed, 110 assertions.
- Full suite `php artisan test`: 98 passed, 476 assertions.
- `vendor/bin/pint --dirty`: PASS.
- `npm run typecheck` (vue-tsc --noEmit): PASS, no output.
- `npm run build`: PASS 2.90s (chunk-size warning only, pre-existing).
- `php artisan route:list --name=booking`: booking.index/store/confirmation public, bookings.index/show under User controller.

## Verification
- Read CreateReservationHold, BookingController, StoreBookingRequest, routes, migration, index.vue, confirmation.vue, i18n files directly; engine lock/overlap path confirmed unchanged.
- Git status: 9 modified + 1 new migration, all within task scope; no engine/config/auth-mechanism changes.
- `vp check` (lint) not run; typecheck + build + Pint + full Pest green.

## Playwright Verification (2026-09-29, localhost:8000)
- Dev DB drift found and reconciled: migrations table held orphan 2026_09_28_081335 (file gone) while 063855 cabins / 063856 translations / 063908 cabin FK / 081607 guest checkout were Pending though tables+data existed. Marked 063855+063856 as Ran (batch 3) via INSERT, then `php artisan migrate --force` applied 063908 + 081607. migrate:status all Ran; reservations now user_id nullable + guest_* + public_token.
- Note: live cabin_translations uses uuid id, file 063856 declares bigint — follow-up for fresh DBs; no action taken (no wipe; seeder lacks cabin seed).
- Guest flow: /id clean console; /id/booking guest fieldset rendered; submitted cabin 1 WY-01 2026-12-10..12 as Budi Santoso → redirect /id/booking/confirmation?token=... rendering WVS-NGU2DWTJ PENDING_PAYMENT, console 0 errors. DB row verified (user_id null, guest snapshot, token set). Test row deleted after.
- Secure confirmation: invalid token and old ?code= both render empty state ('Detail konfirmasi belum tersedia'), no leak.
- Auth flow: /login (Fortify, non-prefixed) login admin@example.com/admin123 → /en/bookings My Bookings; /id/booking prefills Nama Lengkap=admin, Email=admin@example.com, phone empty; authenticated booking cabin 2 2026-12-15..17 phone 081234567890 → WVS-ZTJJ4W59 PENDING_PAYMENT, DB user_id 33 + snapshot; /id/bookings lists WVS-ZTJJ4W59 with ownership link /id/bookings/2. Test row deleted after.
- /en/booking English labels OK; empty submit shows validation errors for cabin_id/check_in/check_out/guest_name/guest_email, console clean.
- Bugs fixed from verification: cabin_id input min=1; children/infants labels i18n (booking.children/infants id Anak/Bayi, en Children/Infants).
- Gates re-run after fixes: BookingFlowTest 9 passed / 110 assertions; full Pest 98 passed / 476 assertions; vue-tsc clean; npm build 2.64s (chunk-size warning, pre-existing); Pint --dirty PASS.
- Test reservations WVS-NGU2DWTJ + WVS-ZTJJ4W59 deleted (0 rows); .playwright-mcp noise removed.

## Documentation
- No PRD/ERD/FLOWCHART body changes (business baseline unchanged; guest checkout is flow detail, engine invariant intact).

## Memory Updates
Obsidian Vault: not available
Code-Base-Memory: not available

## Notes / Follow-ups
- confirmation.vue still renders {code,status} only; token-aware UX copy (invalid-token state) is follow-up.
- confirmationEmpty i18n key exists; invalid-token messaging not yet differentiated from empty.
- Files uncommitted per rules.
