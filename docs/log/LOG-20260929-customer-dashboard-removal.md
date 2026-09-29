# LOG-20260929-customer-dashboard-removal

## Task
Remove customer Dashboard before Reservation Domain Foundation. Final customer flow: Login/Register → Bookings → Booking Detail → Profile. Preserve admin dashboard, public behavior, auth mechanism, locale mechanism. No reservation/payment/voucher/invoice implementation.

## Context / Assumptions
- Laravel 13 + Inertia + Vue monolith at `apps/web`; locale `{locale?}` constrained `id|en`; Fortify auth (views mode).
- Prior structure hardening renamed public navbar/footer to `index.vue` (LOG-20260929-structure-hardening.md, untouched).
- OAuth: Oracle guidance applied — replace `dashboard` route with locale-prefixed `bookings.index` (`/{locale}/bookings`), no dashboard shim; custom Fortify responses via `redirect()->intended(route('bookings.index'))`; persist locale in session because POST `/login` is unprefixed.
- pnpm is the JS runner (`pnpm run typecheck/build/lint:check`); npm fails on workspace config.

## Plan (as approved)
1. Discover all customer-dashboard dependencies (routes/auth/navbar/pages/tests/docs/Wayfinder).
2. Decide locale-aware redirect + route cleanup with Oracle guidance, preserving admin/public/auth.
3. Remove customer dashboard backend (routes, Fortify responses, SetLocale session persist).
4. Remove customer dashboard frontend via visual-engineering (pages, AppSidebar/AppHeader nav, admin breadcrumb, Wayfinder regen).
5. Update only affected tests/docs; write this log including the plan.
6. Verify: tests, typecheck, build, lint, diff inspection.

## Changes
Backend:
- `apps/web/routes/web.php`: removed `Route::inertia('dashboard', 'user/dashboard/index')->name('dashboard')`; auth+verified group now `bookings.index` (GET `bookings` → `user/bookings/index`), `bookings.show` (GET `bookings/{booking}` → `user/bookings/show`), `profile.index` (GET `profile` → `user/profile/index`).
- `apps/web/config/fortify.php`: `home` `/id/dashboard` → `/id/bookings` (fallback only).
- `apps/web/app/Http/Middleware/SetLocale.php`: persists route locale to session.
- `apps/web/app/Support/ResolveLocale.php` (new): resolves session → intended-URL segment → app locale → default `id`; builds localized `bookings.index` URL.
- `apps/web/app/Http/Responses/` (new): `LocaleAwareBookingsRedirect` base + `BookingsLoginResponse`, `BookingsRegisterResponse`, `BookingsTwoFactorLoginResponse`, `BookingsVerifyEmailResponse` (wantsJson branches match vendor defaults).
- `apps/web/app/Providers/FortifyServiceProvider.php`: binds the 4 Fortify response contracts to the above.

Frontend:
- Deleted `resources/js/pages/user/dashboard/index.vue` (user/ now `bookings/` + `profile/` only).
- Created `user/bookings/index.vue`, `user/bookings/show.vue`, `user/profile/index.vue` (minimal single-root, existing i18n keys).
- `AppSidebar.vue` / `AppHeader.vue`: Dashboard nav → locale-aware My Bookings (`bookingsIndex({locale})`, Ticket icon); no `dashboard()` imports.
- `admin/dashboard/index.vue`: file preserved; breadcrumb rewired to `bookingsIndex`.
- `components/public/navbar/index.vue` (+119, auth-aware account dropdown Bookings/Profile/Logout), `i18n/locales/en.ts` + `id.ts` (+profile keys) — from frontend subagent, kept.
- Wayfinder regenerated via `php artisan wayfinder:generate` (actions+routes).

Tests:
- `DashboardTest.php`: guests → login; auth visits `bookings.index`; asserts `Route::has('dashboard')` false.
- `LocaleRoutingTest.php`: `/fr/bookings` → `/id/fr/bookings`; `/bookings` → `/id/bookings`; `/en/bookings` renders `user/bookings/index` locale `en`.
- `AuthenticationTest.php`, `RegistrationTest.php`: redirects now `route('bookings.index')`.
- `EmailVerificationTest.php` (name + asserts), `VerificationNotificationTest.php`: verified redirects `bookings.index` incl. `?verified=1`.

## Business Rules Affected
- Post-login/register/verify/2FA redirect target changed dashboard → bookings (locale-aware). Auth mechanism, verified middleware, 2FA challenge, logout behavior unchanged.
- No booking/payment/pricing/authorization/DB schema changes. Admin dashboard page preserved. No URL changes except removed customer `/dashboard` and added customer `/bookings`, `/bookings/{booking}`, `/profile` under locale prefix.

## Tests
- `php artisan test --compact`: **61 passed, 310 assertions** (baseline was 60; +1 net from DashboardTest rework).
- Focused dashboard/locale/auth suites pass; no regressions.

## Verification
- Tests: PASS (61/61, 310 assertions).
- Typecheck `pnpm run typecheck` (vue-tsc --noEmit): PASS.
- Build `pnpm run build`: PASS (3.00s; chunk-size warning only, pre-existing pattern).
- Lint `pnpm run lint:check` (vp check): PASS — 82 files formatted, 76 files no warnings (after `vp check --fix` on AppHeader.vue).
- `grep dashboard resources/js`: zero matches. `tests/`: only negative assertion in DashboardTest. Docs `dashboard` refs: admin/generic only (DESIGN editorial guidance, PRD admin dashboard, old logs) — no changes needed.

## Documentation
- PRD/DESIGN/ARCHITECTURE/ERD/FLOWCHART: no edits — remaining `dashboard` mentions are admin/generic/historical, not customer-flow.
- Historical logs untouched.

## Memory Updates
- Obsidian Vault: unavailable (no vault access in this session).
- Code-Base-Memory: unavailable (no MCP update made).

## Notes / Follow-ups
- `resources/js/routes/index.ts` may be stale generated output; per-route `resources/js/routes/bookings/index.ts` confirmed present with `/{locale?}/bookings`. Regen via official `wayfinder:generate` only; never hand-edit.
- Bookings pages are structural placeholders (no reservation data wiring) — Reservation Domain Foundation is the separate next task.
- Admin dashboard still has no route pointing at it (pre-existing, intentional per LOG-20260928).
- No commit made (not requested).
