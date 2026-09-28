# LOG-20260928-design-app-css

## Task
User (ID): "saya baru saja memperbarui file DESIGN.md dan app.css saya hapus total jadi tolong kamu implementasikan DESIGN.md ke file app.css dan perbaiki atau sesuaikan penggunaan file app.css untuk semua file yang memangil dan menggunakan style dari style global."

## Context / Assumptions
- DESIGN.md (alpha, 666 lines) adalah sumber kebenaran token: forest green primary #153528, secondary #53685D, accent/star #D8BD94, canvas #FCFBF7, typography Cormorant Garamond (display) + Manrope (sans), radius 0/4/8/14/20/28/full, shadow lift/float, komponen button-primary/secondary, top-nav 80px, card 20px, input 52px, footer-primary + legal-band-active.
- app.css lama (287 lines, git HEAD) diverged dari spec (secondary #d8bd94, on-dark #f3ebd9, legal #153528, scrim #0b2118, font DM Sans) dan sudah dihapus user (0 lines).
- Tailwind v4.1.1. Hanya file publik yang disesuaikan; tidak ada perubahan perilaku/logika bisnis.
- AGENTS.md muncul sebagai modified di working tree — BUKAN perubahan saya, tidak disentuh (milik pihak lain).

## Changes
- `apps/web/resources/css/app.css`: rebuild penuh dari DESIGN.md — :root vars exact spec, shadcn role aliases (background=canvas, foreground=ink, muted=surface-soft, accent-foreground=primary, secondary-foreground=on-primary), @theme font-sans Manrope + font-display Cormorant, token text-*, radius-xs/sm/md/lg/xl, shadow-lift/float, @theme inline color mappings, base layer border-border + body bg-background font-sans.
- `apps/web/vite.config.ts`: font bunny() Instrument Sans -> Manrope 400/600/700 + Cormorant Garamond 600.
- Callers publik diselaraskan ke token baru: home/index.vue, navbar.vue, language-switcher.vue, footer.vue, cabins index/show, about, contact, booking index/confirmation (font-display text-display-*, text-body-sm text-body, rounded-sm/lg, border-hairline, bg-surface-card, hover:bg-primary-active, legal band bg-primary-active text-muted-soft).

## Business Rules Affected
Tidak ada. Perubahan murni token visual + font; tidak menyentuh pricing, availability, hold, payment, voucher, maupun otorisasi.

## Tests
- Pest: 60/60 PASS, 309 assertions (`php artisan test --compact`).
- Tidak ada test baru: perubahan copy/styling/layout-only tidak memerlukan test (test enforcement guidelines).

## Verification
- `npm run typecheck` (vue-tsc --noEmit): PASS, 0 errors.
- `npm run lint` (vp check): awalnya gagal formatting di 4 file (language-switcher, booking/confirmation, cabins/show, home/index); diperbaiki via `npm run check:fix`; hasil akhir PASS (74 files clean).
- `npm run build`: PASS, built in 3.15s (warning chunk-size pre-existing, bukan dari perubahan ini).
- Pest: 60/60 PASS.

## Documentation
- DESIGN.md: diubah oleh user sendiri sebelum task (tidak oleh saya).
- Log ini: docs/log/LOG-20260928-design-app-css.md.

## Memory Updates
Obsidian Vault: unavailable
Code-Base-Memory: unavailable

## Notes / Follow-ups
- Warning build "chunk > 500 kB" pre-existing; pertimbangkan code-splitting bila memburuk (di luar scope task).
- AGENTS.md modified di working tree bukan oleh saya — jangan revert/commit; milik pihak lain.
- Pint tidak dijalankan: tidak ada file PHP yang diubah.
