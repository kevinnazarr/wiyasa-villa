# LOG-20260928-customer-facing-foundation

## Task
Implement the complete customer-facing foundation for Wiyasa Villa: build and wire all public and authenticated customer pages under `resources/js/pages/public` and `resources/js/pages/user` (kebab-case), with locale routes `/id` + `/en` for home, cabins, cabin-detail-by-slug, booking, booking-confirmation, about, contact, dashboard, bookings, booking-detail, profile. Real type-safe contracts via controllers, no fake data, loading/empty/error/not-found states. No payment/locking/voucher/invoice logic — contracts/flow only.

## Context / Assumptions
- Auth + locale routing + layouts + navbar/footer/switcher unchanged.
- Optional `{locale?}` route prefix shifts positional controller args, so implicit route-model binding does not align; controllers read params via `$request->route()`.
- Inertia serializes `JsonResource`/collections with a `data` wrapper, so resources are passed as `->resolve($request)` arrays.
- Reservation records do not exist yet: booking detail/confirmation render not-found/empty states for any code.
- Cabin slug is stable, unique, canonical in both locales.

## Changes
Backend (`apps/web`):
- `database/migrations/2026_09_28_081335_create_cabins_tables.php`: `cabins` (code unique, name, slug unique, description nullable, capacity, base_occupancy, status default ACTIVE, check_in/out nullable, indexes on status+slug) and `cabin_translations` (uuid id, cabin FK cascade, locale char2, name, description nullable, unique cabin+locale).
- `app/Models/Cabin.php` + `CabinTranslation.php`: fillables, `getRouteKeyName` = slug, `translations` HasMany, `displayName`/`displayDescription` with locale fallback.
- `database/factories/CabinFactory.php`: code WY-N, unique slug, capacity 7, base_occupancy 4, status ACTIVE.
- `app/Http/Resources/CabinResource.php`: id/code/slug/name/description/capacity/base_occupancy/status via display helpers.
- Controllers: `Public/PageController` (home/about/contact), `Public/CabinController` (index active+translations; show manual slug lookup + 404), `Public/BookingController` (index catalog + validated `?cabin=` slug; confirmation code+null), `User/DashboardController` (user name/email + empty upcoming/recent), `User/BookingController` (index empty; show code+null), `User/ProfileController` (user name/email).
- `routes/web.php`: controller GET routes in `{locale?}` id|en group (home, cabins.index, cabins.show `cabins/{cabin:slug}`, booking.index, booking.confirmation, about, contact; auth+verified dashboard, bookings.index, bookings.show `bookings/{code}`, profile.show). Wayfinder regenerated.

Frontend:
- `resources/js/types/cabin.ts` (new, exported via `types/index.ts`): CabinSummary/CabinDetail/ReservationSummary/BookingSummary.
- Public pages updated to real contracts: `cabins/index` (links via `cabinsShow` slug + booking CTA), `cabins/show` (detail + booking link with query), `booking/index` (catalog or selected slug), `booking/confirmation` (code or empty).
- New user pages: `user/dashboard/index` (upcoming list w/ `bookingsShow` links), `user/bookings/index`, `user/bookings/show` (code nullable prop), `user/profile/index`.

Tests:
- `tests/Feature/PublicPagesTest.php` rewritten to factory-backed controller contracts: active cabins listed, slug resolves per locale, 404 unknown slug, booking catalog + confirmation, about/contact, dashboard/bookings/detail/profile auth, guest redirects.

## Business Rules Affected
- Cabin identity: canonical unique slug, same in both locales; capacity/base_occupancy defaults 7/4 data-driven via DB, not hardcoded.
- No booking/payment state logic introduced; reservation contracts are empty/null placeholders pending the booking domain.

## Tests
- `php artisan test --filter="PublicPagesTest|LocaleRoutingTest|DashboardTest"`: 31/31 PASS, 251 assertions.
- `php artisan test` (full): 69/69 PASS, 378 assertions.
- `vendor/bin/pint --dirty`: fixed CabinResource + Cabin model; clean after.
- `npm run typecheck`: PASS, exit 0.
- `npm run build`: PASS, exit 0 (pre-existing >500kB chunk-size warning only).

## Verification
- Full flow Home → Cabins → Detail (slug) → Booking (`?cabin=` slug) → Auth → Dashboard → Bookings → Detail → Profile renders in both `/id` and `/en` via controller contracts; not-found states verified by 404 test and null-reservation empty states.

## Documentation
- No PRD/ERD/FLOWCHART changes (contracts only, no business-rule change).

## Memory Updates
- Obsidian Vault: not available
- Code-Base-Memory: not available

## Notes / Follow-ups
- Implicit route-model binding incompatible with the optional `{locale?}` prefix: keep reading route params via `$request->route()` in locale-grouped controllers.
- Pass API Resources to Inertia as `->resolve($request)` arrays to avoid the `data`-wrapper shape mismatch.
- Next: reservations domain (holds, webhook, snapshots) on top of these contracts.
