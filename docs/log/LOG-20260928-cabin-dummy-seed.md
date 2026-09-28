# LOG-20260928-cabin-dummy-seed

## Task
User (ID): "buat data dumy biar saya bisa melakukan testing secara langsung dong. dan verifikasi ini [diagnostic class-not-used CabinFactory di Cabin.php] kenapa di idfinisikan tetapi tidak di gunakan."

## Context / Assumptions
- Dev DB already migrated (cabins + cabin_translations exist).
- Seeder must be idempotent (safe re-run) and cover both locales id/en.
- Dummy cabins are manual-testing fixtures, not production pricing/availability data.

## Changes
- `database/seeders/CabinSeeder.php` (new): 10 cabins WY-01..WY-10 (9 ACTIVE, WY-09 MAINTENANCE), slugs arjuna/srikandi/puntadewa/nakula/sadewa/bima/gatotkaca/semar/petruk/bagong, each with id+en translations via `updateOrCreate`.
- `database/seeders/DatabaseSeeder.php`: added `$this->call(CabinSeeder::class)`.
- No change to `app/Models/Cabin.php`: the `use Database\Factories\CabinFactory` import is referenced by the `/** @use HasFactory<CabinFactory> */` docblock (generic type for `HasFactory` trait → static analysis + IDE inference for `Cabin::factory()`). The `phpNamespaceResolver` extension only counts runtime code references, so it emits a severity-4 hint (not an error). Removing it would break docblock resolution — intentionally kept.

## Business Rules Affected
None (test fixtures only; capacity 7 / base_occupancy 4 follow project baseline).

## Tests
- `vendor/bin/pint` on seeder files: passed.
- `php artisan db:seed --force`: CabinSeeder DONE, tinker confirms 10 cabins / 20 translations.
- `php artisan test --compact`: 69 passed, 378 assertions (no regression).

## Verification
- `php artisan tinker`: `Cabin::count()=10`, `CabinTranslation::count()=20`.
- Manual test URLs (dev): `/id`, `/id/cabins`, `/id/cabins/arjuna`, `/id/booking`, `/id/booking?cabin=srikandi`; auth: admin@example.com / admin123 → `/id/dashboard`, `/id/bookings`, `/id/profile`.

## Documentation
None updated (no business rule change).

## Memory Updates
Obsidian Vault: unavailable
Code-Base-Memory: unavailable

## Notes / Follow-ups
- Re-run anytime: `php artisan db:seed` (idempotent) or `php artisan db:seed --class=CabinSeeder`.
- Vendor LSP noise (Container/Collection undefined types) is pre-existing IDE-server noise, unrelated.
