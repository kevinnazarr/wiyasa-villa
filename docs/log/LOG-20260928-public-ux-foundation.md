# LOG-20260928-public-ux-foundation

## Task
Build the complete public UX foundation and public page structure: layout `public.vue` (Navbar+Content+Footer), navbar in `components/public/navbar/` (reusable responsive + Language Switcher), footer in `components/public/footer/`, pages under `resources/js/pages/public/` (home/index, cabins/index+show, booking/index+confirmation, about/index, contact/index), kebab-case naming, locale routes /id + /en with / redirect to /id, reuse SetLocale middleware, data contracts without fake business data, remove Welcome.vue dependency, keep domain separation.

## Context / Assumptions
- Routes `cabins.index/show`, `booking.index/confirmation`, `about`, `contact` already existed in `routes/web.php` from prior work; `app.ts` layout matcher already mapped `public/*` to `PublicAreaLayout`.
- Wayfinder helpers already generated (`@/routes`, `@/routes/cabins`, `@/routes/booking`) with `locale` default `id`.
- i18n infra: `SUPPORTED_LOCALES ['id','en']`, DEFAULT `id`, MessageSchema derived from `id.ts`; new keys must be added symmetrically to keep types passing.
- Tokens only from `resources/css/app.css` (Tailwind v4: `bg-background text-foreground bg-primary text-on-primary border-border text-muted-foreground`).
- No domain types exist yet in `resources/js/types`; pages use minimal local prop types with optional props + empty states, no fake business data.
- Pre-existing typecheck failures in auth/settings/two-factor files unrelated to this change.

## Changes
- `apps/web/resources/js/layouts/public.vue`: compose `Navbar` + slot + `Footer` + Toaster.
- `apps/web/resources/js/components/public/navbar/navbar.vue`: new, responsive header with Wayfinder links (home/cabins/about/contact), `useCurrentUrl` active states, mobile toggle, login + bookNow CTA.
- `apps/web/resources/js/components/public/navbar/language-switcher.vue`: new, path-preserving switcher (replaces locale segment or prepends), `aria-current` on active.
- `apps/web/resources/js/components/public/footer/footer.vue`: new, brand + tagline + Wayfinder links + copyright with `public.footer.rights` key.
- `apps/web/resources/js/i18n/locales/id.ts` + `en.ts`: add symmetric `public.{home,cabins,booking,about,contact,footer}` namespace.
- `apps/web/resources/js/pages/public/home/index.vue`: rewrite from bare h1 to Head + heading + tagline + bookNow/cabins CTAs + empty-state note.
- `apps/web/resources/js/pages/public/cabins/index.vue`: new, optional `cabins[]` prop, empty state.
- `apps/web/resources/js/pages/public/cabins/show.vue`: new, optional `cabin` prop, empty state.
- `apps/web/resources/js/pages/public/booking/index.vue`: new, optional `cabinId` prop, empty state.
- `apps/web/resources/js/pages/public/booking/confirmation.vue`: new, optional `reservation` prop, empty state.
- `apps/web/resources/js/pages/public/about/index.vue`: new, static + empty state.
- `apps/web/resources/js/pages/public/contact/index.vue`: new, static + empty state.
- `apps/web/tests/Feature/PublicPagesTest.php`: new, 7 tests x 2 locales via dataset, asserting component + locale prop.

## Business Rules Affected
- None. No availability, pricing, voucher, hold, payment, or authorization logic touched. Pages render empty states until domain APIs land.

## Tests
- `php artisan test --compact --filter=PublicPagesTest`: 14 passed, 140 assertions.
- `vendor/bin/pint --dirty --format agent`: passed.
- `npm run typecheck`: FAILS on pre-existing errors only (auth/settings/two-factor `.form` property errors); zero errors in `public/**`, `layouts/public.vue`, `i18n/locales/*`. Verified failing on clean tree via `git stash` too.
- `npm run build` (`vp build`): success, `✓ built in 3.00s`.

## Verification
- All 7 public components render for both `/id/*` and `/en/*` with correct `locale` prop (via PublicPagesTest).
- Navbar links use Wayfinder with current locale; switcher preserves path (segment replace/prepend + query preserved).
- No hardcoded colors; only app.css token utilities.
- No `Welcome.vue` references remain in public pages.

## Documentation
- No changes to `docs/PRD.md`, `ERD.md`, `FLOWCHART.md`, `ARCHITECTURE.md`, `DESIGN.md` (structural UX foundation, not business rule change).

## Memory Updates
Obsidian Vault: unavailable
Code-Base-Memory: unavailable

## Notes / Follow-ups
- `npm run typecheck` pre-existing failures (`Property 'form' does not exist` in ManageTwoFactor, TwoFactorRecoveryCodes, TwoFactorSetupModal, auth/*, settings/*) need separate Wayfinder form-variant fix; out of scope for this task.
- Stale LSP diagnostic references non-existent `resources/js/composables/useLocalePath.ts`; file does not exist, safe to ignore.
- Next: wire real cabin/booking props from controllers, add page sections/ only where complexity demands, differentiate user/admin layouts.
