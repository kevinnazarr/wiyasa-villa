# LOG-20260928-design-tokens-global-css

## Task
Menyesuaikan CSS global (`apps/web/resources/css/app.css`) agar sesuai schema design token pada `docs/DESIGN.md` (file referensi berada di `docs/`).

## Context / Assumptions
- `docs/DESIGN.md` berisi blok YAML token (`colors`, `typography`, `rounded`, `spacing`, `components`) plus prosa pendukung; blok YAML di bagian awal dokumen dipakai sebagai sumber nilai.
- Frontend: Laravel + Inertia + Vue 3 + Tailwind CSS v4 (`@theme` / `@theme inline`) dan shadcn-vue (`components.json` → `new-york-v4`, `cssVariables: true`) dengan token framework `--background`, `--card`, `--primary`, `--border`, `--input`, `--ring`, `--accent`, `--muted`, dst.
- Konflik di dalam DESIGN.md (YAML vs prosa) ditemukan dan diselesaikan secara sadar: `canvas` (#FAF8F2 vs #FFFFFF), `on-primary` (#F3EBD9 vs #FFFFFF), `legal-link` (#153528 vs #0F2A20). Nilai YAML dipakai karena blok YAML adalah referensi token dan konsisten dengan bagian "Brand Color Source" (cream `#F3EBD9` sebagai foreground di atas primary).
- `colors.accent` identik dengan `colors.secondary` (`#D8BD94`) dan tidak direferensikan oleh satu pun token komponen di DESIGN.md, sedangkan utility `accent` framework dipakai lintas ±15 komponen `components/ui` sebagai surface hover/selected. Karena itu `--accent` dipetakan ke `colors.surface-strong` (lihat component token `icon-button-circle`) dan champagne tetap tersedia sebagai `secondary`.
- `colors.muted` adalah warna teks, sedangkan utility `muted` framework adalah surface (dipakai `bg-muted` di Footer/AboutPreview/layout auth). Karena itu `--muted` = `colors.surface-soft` dan warna teks schema diekspos sebagai `--muted-text` → `text-muted-foreground`.
- Dark mode tidak termasuk scope DESIGN.md ("Known Gaps"), tetapi aplikasi menyalakannya secara default lewat system preference (`useAppearance.ts`, `app.blade.php`), sehingga blok `.dark` dipertahankan dengan nilai baseline dark sebelumnya yang di-ekspresikan ulang sebagai token schema.
- `docs/DESIGN.md` mendefinisikan `rounded.full` = 9999px; Tailwind sudah memakai nilai setara untuk `rounded-full`, jadi tidak dioverride.
- Obsidian Vault tidak tersedia di environment ini; Code-Base-Memory MCP tersedia (proyek sudah terindeks) dan di-refresh setelah perubahan.

## Changes
1. `apps/web/resources/css/app.css` — ditulis ulang sebagai sumber tunggal token:
   - `:root`: token mentah DESIGN.md (`--primary`, `--primary-active`, `--primary-disabled`, `--secondary`, `--ink`, `--body`, `--muted-text`, `--muted-soft`, `--on-primary`, `--on-dark`, `--legal-link`, `--canvas`, `--surface-soft`, `--surface-card`, `--surface-strong`, `--hairline`, `--hairline-soft`, `--border-strong`, `--primary-error-text`, `--primary-error-text-hover`, `--star-rating`, `--scrim`).
   - `:root`: alias kompatibilitas shadcn-vue yang diturunkan dari token di atas — `--background: var(--canvas)`, `--foreground: var(--ink)`, `--card`/`--popover` (+`-foreground`), `--primary-foreground: var(--on-primary)`, `--secondary-foreground: var(--ink)`, `--muted: var(--surface-soft)`, `--muted-foreground: var(--muted-text)`, `--accent: var(--surface-strong)`, `--accent-foreground: var(--ink)`, `--destructive: var(--primary-error-text)`, `--destructive-foreground: var(--on-primary)`, `--border: var(--hairline)`, `--input: var(--border-strong)`, `--ring: var(--primary)`, `--radius: 8px` (= `rounded.sm`, dipakai `ui/sonner`).
   - `:root`/`.dark`: `--chart-*` dan `--sidebar-*` tidak ada di schema → nilai baseline sebelumnya dipertahankan apa adanya.
   - `.dark`: override token DESIGN.md memakai nilai baseline dark sebelumnya (`--canvas: hsl(0 0% 3.9%)`, `--surface-soft: hsl(0 0% 16.08%)`, `--surface-strong: hsl(0 0% 14.9%)`, `--ink: hsl(0 0% 98%)`, `--muted-text: hsl(0 0% 63.9%)`, `--hairline: hsl(0 0% 14.9%)`, `--border-strong: hsl(0 0% 14.9%)`, `--primary: hsl(0 0% 98%)`, `--on-primary: hsl(0 0% 9%)`, `--secondary: hsl(0 0% 14.9%)`, `--primary-error-text: hsl(0 84% 60%)`, `--ring: hsl(0 0% 83.1%)`, `--destructive-foreground: hsl(0 0% 98%)`). Token tanpa padanan lama (`--body`, `--muted-soft`, `--hairline-soft`, `--primary-active`, `--primary-disabled`, `--primary-error-text-hover`, `--legal-link`) diterunkan dari skala netral dark yang sudah ada.
   - `@theme` (baru, nilai statis yang di-emit sebagai variabel): `--font-sans: 'DM Sans', Inter, ui-sans-serif, ...`; skala tipografi `--text-*` lengkap 17 token dari `display-xl` sampai `nav-link` beserta `--line-height`, `--letter-spacing`, dan `--font-weight`; skala radius `--radius-xs/sm/md/lg/xl` = 4/8/14/20/28px.
   - `@theme inline`: utility `--color-*` bernama schema (`canvas`, `ink`, `body`, `muted-soft`, `hairline`, `hairline-soft`, `border-strong`, `surface-soft`, `surface-card`, `surface-strong`, `on-primary`, `on-dark`, `legal-link`, `star-rating`, `scrim`, `primary-active`, `primary-disabled`, `primary-error-text`, `primary-error-text-hover`) ditambahkan; alias shadcn-vue yang sudah ada dipertahankan.
   - Dihapus: blok `@layer utilities { body, html { --font-sans: 'Instrument Sans', ... } }` (hardcode font lama yang bertentangan dengan schema; `--font-sans` sekarang di-emit dari `@theme`) dan blok token lama `:root`/`.dark` (digantikan blok baru). Komentar kompatibilitas border Tailwind v3→v4 sempat hilang saat rewrite dan sekali lagi saat `vp check --fix` me-rewrite file; sudah dipulihkan dan terverifikasi bertahan (grep = 1, `vp check --fix` ulang tidak menghapusnya).
2. `apps/web/vite.config.ts` — `bunny('Instrument Sans', { weights: [400, 500, 600] })` → `bunny('DM Sans', { weights: [400, 500, 600, 700] })` agar `--font-sans` sesuai schema benar-benar dimuat (DESIGN.md mencatat font loading belum ditentukan).
3. `apps/web/resources/views/app.blade.php` — warna latar pra-hidrasi `html` dari `oklch(1 0 0)` (putih) → `#faf8f2` (canvas) supaya tidak ada mismatch sebelum CSS dimuat; `html.dark` tidak diubah.

## Business Rules Affected
Tidak ada business rule booking/pricing/payment/voucher/hold yang disentuh. Perubahan murni design token dan presentasi sisi klien; availability, harga, dan status pembayaran tetap server-authoritative sesuai AGENTS.md §7.

## Tests
Dijalankan dari root repo:
- `npm run build` (`vp build`) → sukses (`✓ built in 3.32s`), menghasilkan `assets/app-*.css` 101.94 kB, aset `dm-sans-400/500/600/700` (woff + woff2), dan `fonts-*.css` yang memuat `font-family: "DM Sans"`.
- `npm run typecheck` (`vue-tsc --noEmit`) → sukses, 0 error.
- `npm run lint` (`vp check`) → pass: "All 85 files are correctly formatted" dan "Found no warnings or lint errors in 79 files". Percobaan pertama gagal karena formatting CSS; diperbaiki dengan `npx vp check --fix resources/css/app.css` sehingga hanya `app.css` yang diformat (file in-flight lain tidak tersentuh).
- Verifikasi token terkompilasi (grep bundle): `--primary:#153528`, `--canvas:#faf8f2`, `--surface-soft:#f3efe7`, `--hairline:#e0e6e2`, `--border-strong:#bcc9c2`, `--input:var(--border-strong)`, `--ring:var(--primary)`, `--radius:8px`, `--radius-md:14px`, `--font-sans:"DM Sans", Inter, ...`, `--default-font-family:var(--font-sans)`, dan `.dark{--canvas:#0a0a0a;...}`.
- Probe sementara `resources/js/design-token-probe.ts` (dibuat → build → dihapus → build ulang) membuktikan utility schema tergenerasi: `bg-canvas`, `text-ink`, `text-body`, `border-hairline`, `border-hairline-soft`, `border-border-strong`, `bg-surface-card/soft/strong`, `text-on-primary`, `text-on-dark`, `text-legal-link`, `text-star-rating`, `bg-scrim/50`, `hover:bg-primary-active`, `bg-primary-disabled`, `text-primary-error-text`, dan seluruh `text-display-*` s/d `text-nav-link` (masing-masing berisi font-size + line-height + letter-spacing + font-weight). Setelah probe dihapus, build ulang tidak lagi memuat utility tersebut (tidak ada residu).
- Utility lama tetap tergenerasi: `.bg-background`, `.bg-background/95`, `.bg-card`, `.bg-muted`, `.text-foreground`, `.text-muted-foreground`, `.border-border`, `.border-input`, `.bg-sidebar`, `hover:bg-accent`, `.rounded-md`, `.font-sans`.

## Verification
Verifikasi runtime di browser nyata (chrome-headless-shell-1243 via CDP; skrip sementara `/tmp/design-token-check.mjs`) yang me-load bundle hasil build:
- Light: `bg-primary` = rgb(21,53,40) = `#153528`; `bg-muted` = rgb(243,239,231) = `#F3EFE7`; `bg-background` = rgb(250,248,242) = `#FAF8F2`; `bg-card` = `#FFFFFF`; `text-muted-foreground` = rgb(112,124,117) = `#707C75`; `rounded-md` = 14px; font body = `"DM Sans", Inter, ui-sans-serif, system-ui, sans-serif, ...`.
- Dark (dengan `document.documentElement.classList.add('dark')`, sesuai mekanisme aplikasi): `bg-primary` = rgb(250,250,250); `bg-muted` = rgb(41,41,41); `bg-background` = rgb(10,10,10); `bg-card` = rgb(10,10,10) → alias ikut ter-invert.
- Temuan teknis: `var()` pada alias di-resolve pada elemen tempat deklarasi berada, sehingga class `.dark` harus berada di elemen root (`html`). Implementasi saat ini sudah demikian (`app.blade.php` `@class(['dark' => ...])` dan `useAppearance.ts` → `document.documentElement`). Jika `.dark` dipasang pada ancestor non-root, alias token tidak akan ikut berubah.
- `git status`/diff akhir: hanya `apps/web/resources/css/app.css`, `apps/web/vite.config.ts`, dan `apps/web/resources/views/app.blade.php` yang diubah oleh task ini; file in-flight milik user (mis. `resources/js/layouts/public.vue`, `pages/public/*`, `i18n/*`, `docs/log/LOG-20260928-public-ux-foundation.md`) tidak disentuh.

## Documentation
- `docs/DESIGN.md`, `docs/PRD.md`, `docs/ERD.md`, `docs/ARCHITECTURE.md` tidak diubah. Keputusan pemetaan token dan konflik YAML-vs-prosa didokumentasikan pada komentar `app.css` dan pada log ini.

## Memory Updates
Obsidian Vault: unavailable
Code-Base-Memory: updated (`index_repository` mode `fast` pada proyek `home-kevinnazar-Projects-wiyasa-villa` → status `indexed`, 6954 nodes / 7869 edges). Catatan: `apps/web/resources/css/app.css` dilaporkan `parse_partial` pada rentang baris 5–10 (`@source`/`@custom-variant`) — sinyal best-effort, bukan kegagalan.

## Notes / Follow-ups
- Dampak tampilan light mode: canvas menjadi warm off-white `#FAF8F2` (card tetap putih); CTA primary menjadi forest green dengan teks cream; surface alternatif/hover menjadi netral hangat (`#F3EFE7` / `#E4E9E5`); border input memakai border-strong `#BCC9C2` dan focus ring mengikuti primary; skala radius global berubah menjadi `rounded-xs/sm/md/lg/xl` = 4/8/14/20/28px.
- Selisih tingkat komponen: komponen shadcn-vue memakai `rounded-md` (button/input → kini 14px) dan `rounded-xl` (Card → kini 28px), sedangkan token komponen DESIGN.md menyebut button 8px (`rounded.sm`) dan card 14px (`rounded.md`). Penyesuaian class per komponen berada di luar scope task ini (hanya CSS global) dan perlu task lanjutan bila presisi per komponen diinginkan.
- Spacing schema (`xxs`…`section`) setara dengan skala default Tailwind berbasis 4px (`xs` = `p-1`, `sm` = `p-2`, `md` = `p-3`, `base` = `p-4`, `lg` = `p-6`, `xl` = `p-8`, `xxl` = `p-12`, `section` = `p-16`), sehingga tidak diduplikasi menjadi token baru.
- Breakpoint DESIGN.md (744/1128/1440) belum diterapkan; saat ini masih memakai default Tailwind. Ini bagian prosa (bukan blok token) dan mengubahnya berdampak luas pada komponen, jadi sengaja tidak dilakukan.
- DESIGN.md masih belum konsisten untuk `canvas`, `on-primary`, dan `legal-link` (YAML vs prosa). Implementasi memakai nilai YAML; disarankan menyelaraskan prosa DESIGN.md pada task dokumentasi terpisah.
- Dark mode tetap di luar design system. Bila nanti masuk scope, blok `.dark` perlu diturunkan dari token brand (bukan baseline netral lama), dan `--chart-*`/`--sidebar-*` perlu ditinjau.


