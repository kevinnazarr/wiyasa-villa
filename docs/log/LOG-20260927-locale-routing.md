# LOG-20260927-locale-routing

## Task
Implement locale-aware routing foundation.
- Support locale-prefixed routes: `/id/...` and `/en/...`.
- Define `id` and `en` as the only supported route locales.
- Integrate locale-aware routing with the existing Vue i18n locale configuration.
- Ensure the locale is resolved from the URL and applied to the active i18n locale.
- Add a clean fallback/redirect behavior for unsupported or missing locales according to the existing architecture.
- Preserve existing Inertia routing and do not introduce backend locale persistence, `users.locale`, session/cookie synchronization, or language-switcher UI.
- Update/add focused tests for the routing behavior.
- Verify with the existing typecheck, tests, and build commands.

## Context / Assumptions
- Default locale is `id`, fallback locale is `en` as defined in `docs/ARCHITECTURE.md` and `docs/PRD.md`.
- Supported route locales strictly restricted to `['id', 'en']`.
- Root `/` and missing or unsupported locale prefixes cleanly redirect to `/id/...`.
- In Wayfinder TS route generation, `{locale?}` allows generated route helpers to preserve zero-argument usage in existing components while defaulting to active locale.
- Inertia page props share `locale` via `HandleInertiaRequests`, which syncs on client startup and navigation to `vue-i18n`.

## Changes
- `apps/web/app/Http/Middleware/SetLocale.php`: Created middleware checking route parameter or URL segment for supported locale (`id`, `en`). Calls `App::setLocale($locale)` and sets `URL::defaults(['locale' => $locale])`.
- `apps/web/bootstrap/app.php`: Registered `SetLocale::class` in the web middleware stack before `HandleInertiaRequests::class`.
- `apps/web/app/Http/Middleware/HandleInertiaRequests.php`: Added `'locale' => fn () => app()->getLocale()` to shared Inertia props.
- `apps/web/routes/web.php`: Added root redirect `/` -> `/id`, wrapped application routes in `Route::prefix('{locale?}')->whereIn('locale', ['id', 'en'])`, and added a fallback redirect for missing/unsupported locales to `/id/...`.
- `apps/web/resources/js/i18n/index.ts`: Added `isSupportedLocale` and `setLocale(locale: string)` helper functions.
- `apps/web/resources/js/app.ts`: Initialized active locale from `initialPage.props.locale` and listened to Inertia `navigate` event to sync `setLocale(event.detail.page.props.locale)`.
- `apps/web/tests/Feature/LocaleRoutingTest.php`: Added 6 Pest tests covering root redirect, `/id` route, `/en` route, unsupported locale fallback/redirect, deep path fallback/redirect, and authenticated route with locale prefix.

## Business Rules Affected
- Internationalization routing baseline: customer-facing URLs are locale-scoped to `/id` or `/en`.
- URL is the immediate authority for presentation locale in this routing layer.

## Tests
- `vendor/bin/pest tests/Feature/LocaleRoutingTest.php`: 6 passed, 39 assertions.
- `npm run types:check`: `vue-tsc --noEmit` passed with 0 errors.
- `npm run build`: `vp build` Vite build succeeded with 0 errors.

## Verification
- Route redirection: verified `/` -> `/id` (302).
- Route locale setting: verified `/id` sets locale to `id`, `/en` sets locale to `en`.
- Unsupported locale: verified `/fr/dashboard` redirects to `/id/fr/dashboard`.
- Unprefixed deep route: verified `/dashboard` redirects to `/id/dashboard`.
- Inertia shared prop: verified `page.props.locale` matches active URL locale.
- TypeScript typing: verified type check and production bundle build with zero errors.

## Documentation
- `docs/ARCHITECTURE.md` and `docs/PRD.md` requirements aligned.

## Memory Updates
Obsidian Vault: not applicable
Code-Base-Memory: not applicable

## Notes / Follow-ups
- Language switcher UI, cookie/session synchronization, and `users.locale` persistence are reserved for subsequent phases per scope instructions.
