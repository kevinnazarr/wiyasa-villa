---
version: alpha
name: Wiyasa Villa Design System
description: Sistem desain untuk Wiyasa Villa Dieng yang memadukan canvas putih hangat, forest green dari identitas logo, dan champagne gold sebagai aksen premium. Tipografi menggabungkan serif editorial untuk headline dengan sans-serif modern untuk UI dan body, menggunakan radius lembut, whitespace lapang, imagery alam sebagai focal point, dan elevation yang sangat halus.

colors:
  primary: "#153528"
  primary-active: "#0E2B20"
  primary-disabled: "#9BAAA3"
  primary-error-text: "#B42318"
  primary-error-text-hover: "#912018"
  secondary: "#53685D"
  accent: "#D8BD94"
  ink: "#19352B"
  body: "#52615A"
  muted: "#7A8781"
  muted-soft: "#AAB4AF"
  hairline: "#DDE4DF"
  hairline-soft: "#E9EEEB"
  border-strong: "#B9C8C0"
  canvas: "#FCFBF7"
  surface-soft: "#F4F6F2"
  surface-card: "#FFFFFF"
  surface-strong: "#E8EDE9"
  on-primary: "#F8F3E8"
  on-dark: "#FFFFFF"
  legal-link: "#3F6655"
  star-rating: "#D8BD94"
  scrim: "#10251D"

typography:
  display-xl:
    fontFamily: "'Cormorant Garamond', Georgia, serif"
    fontSize: 64px
    fontWeight: 600
    lineHeight: 1.02
    letterSpacing: -1.28px
  display-lg:
    fontFamily: "'Cormorant Garamond', Georgia, serif"
    fontSize: 48px
    fontWeight: 600
    lineHeight: 1.05
    letterSpacing: -0.72px
  display-md:
    fontFamily: "'Cormorant Garamond', Georgia, serif"
    fontSize: 40px
    fontWeight: 600
    lineHeight: 1.10
    letterSpacing: -0.48px
  display-sm:
    fontFamily: "'Cormorant Garamond', Georgia, serif"
    fontSize: 32px
    fontWeight: 600
    lineHeight: 1.12
    letterSpacing: -0.32px
  title-md:
    fontFamily: "'Manrope', Arial, sans-serif"
    fontSize: 20px
    fontWeight: 700
    lineHeight: 1.30
    letterSpacing: -0.10px
  title-sm:
    fontFamily: "'Manrope', Arial, sans-serif"
    fontSize: 17px
    fontWeight: 700
    lineHeight: 1.35
    letterSpacing: 0
  rating-display:
    fontFamily: "'Cormorant Garamond', Georgia, serif"
    fontSize: 64px
    fontWeight: 600
    lineHeight: 1.00
    letterSpacing: -1px
  body-md:
    fontFamily: "'Manrope', Arial, sans-serif"
    fontSize: 16px
    fontWeight: 400
    lineHeight: 1.65
    letterSpacing: 0
  body-sm:
    fontFamily: "'Manrope', Arial, sans-serif"
    fontSize: 14px
    fontWeight: 400
    lineHeight: 1.55
    letterSpacing: 0
  caption:
    fontFamily: "'Manrope', Arial, sans-serif"
    fontSize: 13px
    fontWeight: 600
    lineHeight: 1.40
    letterSpacing: 0
  caption-sm:
    fontFamily: "'Manrope', Arial, sans-serif"
    fontSize: 12px
    fontWeight: 400
    lineHeight: 1.40
    letterSpacing: 0
  badge:
    fontFamily: "'Manrope', Arial, sans-serif"
    fontSize: 11px
    fontWeight: 700
    lineHeight: 1.20
    letterSpacing: 0.02px
  micro-label:
    fontFamily: "'Manrope', Arial, sans-serif"
    fontSize: 11px
    fontWeight: 700
    lineHeight: 1.35
    letterSpacing: 0.80px
  uppercase-tag:
    fontFamily: "'Manrope', Arial, sans-serif"
    fontSize: 10px
    fontWeight: 700
    lineHeight: 1.25
    letterSpacing: 1.20px
    textTransform: uppercase
  button-md:
    fontFamily: "'Manrope', Arial, sans-serif"
    fontSize: 14px
    fontWeight: 700
    lineHeight: 1.20
    letterSpacing: 0
  button-sm:
    fontFamily: "'Manrope', Arial, sans-serif"
    fontSize: 13px
    fontWeight: 700
    lineHeight: 1.25
    letterSpacing: 0
  link:
    fontFamily: "'Manrope', Arial, sans-serif"
    fontSize: 14px
    fontWeight: 600
    lineHeight: 1.50
    letterSpacing: 0
  nav-link:
    fontFamily: "'Manrope', Arial, sans-serif"
    fontSize: 13px
    fontWeight: 700
    lineHeight: 1.25
    letterSpacing: 0.10px

rounded:
  none: 0px
  xs: 4px
  sm: 8px
  md: 14px
  lg: 20px
  xl: 28px
  full: 9999px

spacing:
  xxs: 2px
  xs: 4px
  sm: 8px
  md: 12px
  base: 16px
  lg: 24px
  xl: 32px
  xxl: 48px
  section: 80px

components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.on-primary}"
    typography: "{typography.button-md}"
    rounded: "{rounded.sm}"
    padding: 14px 24px
    height: 48px
  button-primary-active:
    backgroundColor: "{colors.primary-active}"
    textColor: "{colors.on-primary}"
    rounded: "{rounded.sm}"
  button-primary-disabled:
    backgroundColor: "{colors.primary-disabled}"
    textColor: "{colors.on-primary}"
    rounded: "{rounded.sm}"
  button-secondary:
    backgroundColor: "{colors.canvas}"
    textColor: "{colors.primary}"
    typography: "{typography.button-md}"
    rounded: "{rounded.sm}"
    padding: 13px 23px
    height: 48px
    border: "1px solid {colors.border-strong}"
  button-tertiary-text:
    backgroundColor: transparent
    textColor: "{colors.primary}"
    typography: "{typography.button-md}"
  button-pill:
    backgroundColor: "{colors.accent}"
    textColor: "{colors.primary}"
    typography: "{typography.button-sm}"
    rounded: "{rounded.full}"
    padding: 10px 20px
  search-orb:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.on-primary}"
    rounded: "{rounded.full}"
    height: 48px
  icon-button-circle:
    backgroundColor: "{colors.surface-strong}"
    textColor: "{colors.ink}"
    rounded: "{rounded.full}"
    height: 40px
  icon-button-outline:
    backgroundColor: "{colors.canvas}"
    textColor: "{colors.ink}"
    rounded: "{rounded.full}"
    height: 40px
    border: "1px solid {colors.hairline}"
  top-nav:
    backgroundColor: "{colors.surface-card}"
    textColor: "{colors.ink}"
    typography: "{typography.nav-link}"
    height: 80px
  product-tab-active:
    backgroundColor: transparent
    textColor: "{colors.primary}"
    typography: "{typography.nav-link}"
    rounded: "{rounded.none}"
  product-tab-inactive:
    backgroundColor: transparent
    textColor: "{colors.muted}"
    typography: "{typography.nav-link}"
  search-bar-pill:
    backgroundColor: "{colors.surface-card}"
    textColor: "{colors.ink}"
    typography: "{typography.body-sm}"
    rounded: "{rounded.full}"
    padding: 8px 8px 8px 20px
    height: 64px
    border: "1px solid {colors.hairline}"
  search-field-segment:
    backgroundColor: transparent
    textColor: "{colors.ink}"
    typography: "{typography.caption}"
    padding: 8px 20px
  category-strip:
    backgroundColor: "{colors.canvas}"
    textColor: "{colors.muted}"
    typography: "{typography.button-sm}"
  category-tab-active:
    backgroundColor: transparent
    textColor: "{colors.primary}"
    typography: "{typography.button-sm}"
    rounded: "{rounded.none}"
  card:
    backgroundColor: "{colors.surface-card}"
    textColor: "{colors.ink}"
    typography: "{typography.body-sm}"
    rounded: "{rounded.lg}"
  card-photo:
    rounded: "{rounded.lg}"
  badge:
    backgroundColor: "{colors.surface-card}"
    textColor: "{colors.primary}"
    typography: "{typography.badge}"
    rounded: "{rounded.full}"
    padding: 6px 10px
  new-tag:
    backgroundColor: "{colors.accent}"
    textColor: "{colors.primary}"
    typography: "{typography.uppercase-tag}"
    rounded: "{rounded.full}"
    padding: 4px 8px
  text-input:
    backgroundColor: "{colors.surface-card}"
    textColor: "{colors.ink}"
    typography: "{typography.body-md}"
    rounded: "{rounded.sm}"
    padding: 14px 14px
    height: 52px
    border: "1px solid {colors.hairline}"
  footer-light:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.on-primary}"
    typography: "{typography.body-sm}"
    padding: 64px 80px
  footer-link:
    backgroundColor: transparent
    textColor: "{colors.on-primary}"
    typography: "{typography.body-sm}"
  legal-band:
    backgroundColor: "{colors.primary-active}"
    textColor: "{colors.muted-soft}"
    typography: "{typography.caption-sm}"

---

## Overview

Wiyasa Villa menggunakan arah visual **modern highland hospitality**: bersih, tenang, hangat, dan premium tanpa terasa berlebihan. Canvas utama adalah putih hangat (`#FCFBF7`) agar fotografi Dieng, tekstur kayu, kabut, pegunungan, dan arsitektur villa menjadi fokus utama. Forest green dari identitas logo menjadi warna brand sekaligus anchor visual untuk navigation, CTA, heading penting, dan elemen yang membutuhkan kontras kuat. Champagne gold digunakan secara hemat sebagai aksen premium yang mengikat visual matahari, kehangatan, dan nuansa natural pada logo.

Tipografi menggunakan **Cormorant Garamond** untuk display/headline dan **Manrope** untuk interface, body, navigation, serta form. Kombinasi ini mempertahankan karakter hospitality yang elegan sekaligus membuat website tetap modern dan mudah dipindai. Shape language menggunakan rounded corners yang lembut, tetapi tidak terlalu pill-heavy; elevation rendah dan whitespace luas menjaga kesan tenang serta editorial.

### Brand Direction

- **Primary visual cue:** forest green + warm white.
- **Luxury cue:** champagne gold digunakan sebagai accent, bukan warna dominan.
- **Highland cue:** imagery landscape, kabut, sunrise, bukit, kayu, dan natural texture.
- **Hospitality cue:** UI terasa welcoming, ringan, dan tidak terlalu corporate.
- **Logo treatment:** gunakan logo utama pada background putih/warm-white; gunakan versi inverse bila ditempatkan pada forest-green surface.
- **Do not overuse gold:** gold sebaiknya muncul pada CTA sekunder, rating, divider, icon highlight, atau micro-detail.

**Key Characteristics:**
- Forest green menjadi brand anchor dan CTA utama.
- Warm white menjadi base canvas agar visual villa dan landscape terasa premium.
- Champagne gold menjadi aksen hospitality yang terkontrol.
- Serif display memberi karakter editorial/luxury; sans-serif menjaga UI tetap modern.
- Navigation sederhana dengan whitespace luas dan logo sebagai focal point.
- Search/booking surface berbentuk rounded pill dengan border tipis.
- Card memakai radius lembut dan shadow minimal; kualitas visual terutama datang dari fotografi.
- Spacing relatif lapang untuk menciptakan rasa tenang seperti pengalaman menginap di pegunungan.

---

## Colors

### Brand & Accent
- **Primary** (`{colors.primary}` — `#153528`): warna brand utama; gunakan untuk CTA utama, navigation text penting, footer, overlay, dan section yang membutuhkan anchor kuat.
- **Primary Active** (`{colors.primary-active}` — `#0E2B20`): hover/press state primary button dan interactive dark surfaces.
- **Primary Disabled** (`{colors.primary-disabled}` — `#9BAAA3`): disabled state; jangan digunakan untuk teks body normal.
- **Secondary / Accent** (`{colors.secondary}` — `#53685D`): secondary green untuk supporting UI, icon, subtle highlights, dan intermediate contrast.
- **Accent** (`{colors.accent}` — `#D8BD94`): champagne gold untuk rating, small highlights, decorative divider, selected premium indicator, dan CTA sekunder tertentu.

### Surface
- **Canvas** (`{colors.canvas}` — `#FCFBF7`): background default halaman; warm white dipilih agar tidak terlalu sterile dibanding pure white.
- **Surface Soft** (`{colors.surface-soft}` — `#F4F6F2`): background section ringan, filter area, amenity blocks, atau alternate content band.
- **Surface Card** (`{colors.surface-card}` — `#FFFFFF`): kartu, modal, form field, dan surface yang perlu terlihat elevated dari canvas.
- **Surface Strong** (`{colors.surface-strong}` — `#E8EDE9`): icon button, selected utility state, subtle chip background, dan control surface.

### Hairlines & Borders
- **Hairline** (`{colors.hairline}` — `#DDE4DF`): border default 1px pada input, card outline, divider.
- **Hairline Soft** (`{colors.hairline-soft}` — `#E9EEEB`): divider sangat ringan dan section separation.
- **Border Strong** (`{colors.border-strong}` — `#B9C8C0`): focus-adjacent border, outlined CTA, atau state yang membutuhkan definisi lebih kuat.

### Text
- **Ink** (`{colors.ink}` — `#19352B`): headline, navigation, primary body text, dan high-priority content.
- **Body** (`{colors.body}` — `#52615A`): paragraph, supporting copy, description, dan metadata.
- **Muted** (`{colors.muted}` — `#7A8781`): inactive navigation, secondary metadata, placeholder.
- **Muted Soft** (`{colors.muted-soft}` — `#AAB4AF`): disabled text dan low-priority utility content.
- **On Primary** (`{colors.on-primary}` — `#F8F3E8`): text/icon di atas forest-green primary surfaces.
- **On Dark** (`{colors.on-dark}` — `#FFFFFF`): high-contrast content di atas dark photography overlay atau dark surface.

### Semantic
- **Error** (`{colors.primary-error-text}` — `#B42318`): validation error, failed booking, atau destructive feedback.
- **Error Hover** (`{colors.primary-error-text-hover}` — `#912018`): hover/active state untuk error interaction.
- **Legal Link** (`{colors.legal-link}` — `#3F6655`): legal/privacy links di light surfaces.

### Scrim
- **Scrim** (`{colors.scrim}` — `#10251D` at 60% opacity): modal backdrop, mobile navigation overlay, image lightbox backdrop, dan blocking interaction state.

### Color Usage Rules

1. Gunakan **warm white + white card** sebagai mayoritas surface.
2. Forest green harus menjadi warna paling dominan setelah white surfaces.
3. Gold hanya sebagai accent; hindari section besar full-gold.
4. Jangan menggunakan banyak shade green dalam satu komponen kecuali untuk state.
5. Pastikan teks body tetap memenuhi contrast yang layak; jangan memakai gold sebagai body text.
6. Untuk hero image, gunakan dark-green overlay tipis hanya jika dibutuhkan untuk menjaga readability.

---

## Typography

### Font Family
Sistem menggunakan **Cormorant Garamond** untuk display/headline dan **Manrope** untuk body, navigation, form, button, serta metadata.

Fallback display: `Georgia, serif`.  
Fallback UI: `Arial, sans-serif`.

Cormorant Garamond dipilih untuk menangkap karakter serif elegan pada wordmark Wiyasa Villa tanpa membuat keseluruhan interface terasa klasik. Manrope memberi counterweight yang lebih contemporary untuk booking flow, navigation, dan functional UI.

### Hierarchy

| Token | Size | Weight | Line Height | Letter Spacing | Use |
|---|---:|---:|---:|---:|---|
| `{typography.display-xl}` | 64px | 600 | 1.02 | -1.28px | Hero headline desktop |
| `{typography.display-lg}` | 48px | 600 | 1.05 | -0.72px | Page hero / major section |
| `{typography.display-md}` | 40px | 600 | 1.10 | -0.48px | Section headline |
| `{typography.display-sm}` | 32px | 600 | 1.12 | -0.32px | Compact section / card group |
| `{typography.title-md}` | 20px | 700 | 1.30 | -0.10px | Card title / component heading |
| `{typography.title-sm}` | 17px | 700 | 1.35 | 0 | Small heading |
| `{typography.body-md}` | 16px | 400 | 1.65 | 0 | Primary body copy |
| `{typography.body-sm}` | 14px | 400 | 1.55 | 0 | Metadata / secondary copy |
| `{typography.caption}` | 13px | 600 | 1.40 | 0 | Form label / compact label |
| `{typography.caption-sm}` | 12px | 400 | 1.40 | 0 | Fine print |
| `{typography.badge}` | 11px | 700 | 1.20 | 0.02px | Badge |
| `{typography.button-md}` | 14px | 700 | 1.20 | 0 | Primary CTA |
| `{typography.button-sm}` | 13px | 700 | 1.25 | 0 | Secondary CTA / chip |
| `{typography.nav-link}` | 13px | 700 | 1.25 | 0.10px | Desktop navigation |

### Principles

- Headline harus terasa **editorial dan atmospheric**, bukan seperti dashboard.
- Gunakan serif terutama untuk kata/kalimat yang ingin menjadi visual focal point.
- Body dan functional text harus selalu menggunakan sans-serif.
- Hindari terlalu banyak font weight; 400 dan 700 Manrope sudah cukup untuk mayoritas UI.
- Gunakan uppercase hanya untuk micro-label, category, dan utility metadata.
- Hero headline idealnya pendek, 1–2 baris, agar fotografi tetap memiliki ruang.
- Jangan menggunakan gold untuk headline panjang; gunakan forest green atau white.
- Untuk bahasa Indonesia, pertahankan line-height yang cukup lega agar paragraph terasa ringan.

### Note on Font Substitutes

Jika Cormorant Garamond tidak tersedia, gunakan **Georgia** sebagai substitute. Jika Manrope tidak tersedia, gunakan **Arial** atau system sans-serif. Untuk production web, self-hosted font atau provider font resmi dapat digunakan untuk menjaga consistency dan performa.

---

## Layout

### Spacing System
- **Base unit:** 4px.
- **Tokens:** `{spacing.xxs}` 2px · `{spacing.xs}` 4px · `{spacing.sm}` 8px · `{spacing.md}` 12px · `{spacing.base}` 16px · `{spacing.lg}` 24px · `{spacing.xl}` 32px · `{spacing.xxl}` 48px · `{spacing.section}` 80px
- **Section padding (vertical):** `{spacing.section}` (80px) untuk desktop major sections; 56–64px pada tablet; 40–48px pada mobile.
- **Card internal padding:** 20–24px untuk card besar; 12–16px untuk compact meta block.
- **Gutters:** 24px desktop untuk grid; 16px mobile. Footer menggunakan 24–32px antar column.

### Grid & Container
- **Max content width:** 1280px.
- **Primary grid:** 12-column desktop grid dengan gutter 24px.
- **Tablet grid:** 8-column grid dengan gutter 20px.
- **Mobile grid:** 4-column grid dengan gutter 16px.
- **Hero:** full-width imagery dengan content overlay atau split layout; hindari hero yang terlalu padat.
- **Property/detail page:** gunakan 2-column desktop layout bila ada booking panel; content 7–8 columns dan booking rail 4–5 columns.
- **Gallery:** gunakan asymmetric/editorial composition sesekali untuk menjaga karakter premium.
- **Footer:** 4 column desktop; collapse menjadi 2 lalu 1 column pada viewport lebih kecil.

### Whitespace Philosophy

Whitespace adalah bagian utama dari brand. Wiyasa Villa tidak menggunakan density seperti dashboard atau marketplace. Berikan ruang yang cukup di sekitar headline, photography, CTA, dan section boundaries. Konten harus terasa seperti editorial travel/hospitality: **large image → short copy → clear action → generous breathing room**.

---

## Elevation

Sistem memiliki **dua shadow tier utama** dan satu scrim.

- **Flat (no shadow):** mayoritas surface — body, hero, footer, section background.
- **Card / hover:** `box-shadow: 0 8px 30px rgba(21, 53, 40, 0.08)` — digunakan ketika card interactive di-hover atau booking panel perlu terangkat.
- **Floating / modal:** `box-shadow: 0 20px 60px rgba(21, 53, 40, 0.14)` — dropdown besar, date picker, modal, lightbox.
- **Modal scrim:** `{colors.scrim}` at 60% opacity.

### Elevation Principles

- Default card sebaiknya flat atau hanya memiliki shadow sangat tipis.
- Border lebih disukai daripada shadow untuk membedakan surface pada halaman putih.
- Shadow harus memiliki tint yang berasal dari green/neutral brand, bukan black murni.
- Hover boleh menaikkan elevation sedikit; jangan membuat UI terasa floating everywhere.

---

## Components

### Buttons

**`button-primary`** — Forest green fill, warm-white text, 8px radius, 14×24px padding, 48px height, Manrope 700. Digunakan untuk aksi utama seperti **Pesan Sekarang**, **Cek Ketersediaan**, dan **Reservasi**.

**`button-primary-active`** — Menggunakan `#0E2B20` untuk press/active state. Transition 150–200ms.

**`button-primary-disabled`** — Menggunakan `#9BAAA3`; tidak boleh terlihat clickable.

**`button-secondary`** — Warm-white/white surface dengan forest-green text dan border `#B9C8C0`. Digunakan untuk aksi sekunder seperti **Lihat Detail**, **Pelajari Villa**, atau Cancel.

**`button-tertiary-text`** — Text-only action tanpa surface. Digunakan untuk **Lihat Semua**, **Selengkapnya**, dan modal close/action yang tidak membutuhkan emphasis.

**`button-pill`** — Champagne-gold pill dengan forest-green text. Digunakan untuk highlight premium, package tag, atau secondary promotional CTA; bukan sebagai pengganti primary CTA.

### Search Surface

**`search-bar-pill`** — Signature booking/search surface. White card, border 1px `#DDE4DF`, 64px height, fully rounded, dengan shadow ringan hanya ketika floating di atas hero. Cocok untuk input tanggal, jumlah tamu, dan CTA availability.

**`search-orb`** — Circular forest-green button di ujung search bar. 48px diameter/height, white icon, digunakan untuk submit/search/availability.

### Top Navigation

**`top-nav`** — White surface, 80px desktop height, logo di kiri, navigation di tengah/sekitar center, booking CTA di kanan. Gunakan subtle bottom border atau shadow tipis ketika navigation fixed/sticky.

**`product-tab-active`** / **`product-tab-inactive`** — Active state forest green dengan visual indicator tipis; inactive menggunakan muted green-gray. Hindari underline yang terlalu berat.

**`new-tag`** — Champagne-gold pill untuk fitur baru atau package highlight.

### Cards

**`card`** / **`property-card`** — White surface, 20px radius, image-first composition, metadata singkat, title serif atau strong sans-serif sesuai hierarchy, dan CTA yang jelas. Card tidak perlu heavy shadow.

**`card-photo`** — 20px radius pada seluruh photo plate. Untuk image gallery, gunakan `object-fit: cover` dan aspect ratio konsisten.

**`badge`** / **`guest-favorite-badge`** — White translucent/solid pill di atas image dengan forest-green text. Gunakan secukupnya agar photography tidak tertutup terlalu banyak.

### Forms

**`text-input`** — White surface, 1px hairline border, 8px radius, 52px height, 14px horizontal padding, Manrope 400. Focus state menggunakan forest-green border dan subtle outer ring.

Recommended states:
- Default: `#DDE4DF`
- Hover: `#B9C8C0`
- Focus: `#153528` + 3px translucent green ring
- Error: `#B42318`
- Disabled: `#F4F6F2` + muted text

### Booking Form

- Booking panel harus terasa simple dan reassuring.
- Label gunakan caption 13px/600.
- Harga total harus menggunakan title-md atau display-sm bila menjadi primary decision point.
- Availability CTA harus primary.
- Gunakan gold hanya untuk supporting highlight seperti `Best value`, rating, atau selected package.

### Gallery

- Hero/gallery image harus menjadi salah satu elemen terbesar pada halaman.
- Gunakan rounded 20px untuk gallery container.
- Overlay gradient hanya ketika diperlukan untuk text readability.
- Hindari filter berat; pertahankan warna alam agar green brand tidak bertabrakan dengan landscape.

### Footer

**`footer-light`** — Forest-green background, warm-white text, padding 64px 80px desktop. Logo dapat menggunakan inverse treatment. Gunakan gold hanya sebagai divider/accent kecil.

**`footer-link`** — Warm-white dengan opacity/contrast lebih rendah untuk secondary links. Hover dapat berubah menjadi white penuh atau champagne gold.

**`legal-band`** — Darker green `#0E2B20` untuk copyright, privacy, terms, dan utility links.

---

## Responsive Behavior

| Name | Width | Key Changes |
|---|---|---|
| Mobile | < 744px | Compact logo, hamburger navigation, hero text 1–3 lines, search menjadi stacked card, card 1-up, CTA full-width bila diperlukan |
| Tablet | 744–1128px | Nav mulai disederhanakan, grid 2-up, search lebih compact, hero dapat menjadi split layout |
| Desktop | 1128–1440px | Full nav, full booking/search surface, grid 3–4-up, 2-column detail/booking layout |
| Wide | > 1440px | Content tetap capped sekitar 1280px, outer gutter bertambah, hero imagery dapat lebih immersive tanpa memperlebar text block |

### Touch Targets
- Primary CTAs minimum **48×48px**.
- Icon button minimum **40×40px**, ideal 44–48px pada mobile.
- Search orb minimum **48×48px**.
- Date picker cells minimum **44×44px**.
- Mobile nav item minimum **44px** height.

### Collapsing Strategy
- Desktop navigation → hamburger pada mobile.
- Horizontal search bar → stacked booking/search card.
- 4-column card grid → 2-column tablet → 1-column mobile.
- Booking rail → inline section atau sticky bottom CTA pada mobile.
- Gallery grid → horizontal carousel atau 1 large image + thumbnail strip.
- Footer 4 column → 2 column → 1 column.
- Avoid sticky elements that cover more than 20% of the mobile viewport.

---

## Accessibility & Interaction

- Body text harus mempertahankan contrast yang memadai terhadap warm-white canvas.
- Jangan menyampaikan informasi hanya melalui warna; state harus dibedakan dengan icon, text, border, atau shape.
- Semua CTA dan form control harus memiliki visible focus state.
- Motion harus subtle: 150–250ms untuk hover/focus, tanpa parallax yang mengganggu readability.
- Gunakan semantic HTML: `header`, `nav`, `main`, `section`, `article`, `footer`.
- Semua imagery dekoratif harus memiliki empty alt; imagery informatif harus memiliki alt yang deskriptif.
- Booking/availability flow harus tetap usable dengan keyboard.
- Error message harus berada dekat field yang bermasalah dan menjelaskan cara memperbaikinya.

---

## Imagery & Visual Content

### Photography Direction

Foto merupakan salah satu primary visual asset Wiyasa Villa. Prioritaskan:
- lanskap Dieng dan pegunungan;
- sunrise, kabut, dan golden-hour;
- exterior villa dan architectural detail;
- kamar dengan natural light;
- material kayu, linen, batu, dan tekstur natural;
- human moments yang natural, bukan stock-photo pose.

### Image Treatment

- Prefer natural color grading.
- Hindari saturation berlebihan.
- Pertahankan green/earth palette agar menyatu dengan brand.
- Gunakan rounded corners 20px pada major image plates.
- Gunakan dark overlay secara selektif untuk text-on-image.
- Jangan menambahkan decorative graphics yang bersaing dengan landscape.

---

## Logo Usage

Logo Wiyasa Villa memiliki kombinasi visual **forest green + champagne/beige** dengan motif pegunungan, matahari, dan rumah. Identitas ini sebaiknya menjadi anchor visual yang konsisten.

### Preferred Usage
- Primary logo pada canvas `#FCFBF7` atau `#FFFFFF`.
- Inverse logo pada forest-green background.
- Favicon/icon mark digunakan untuk browser/app icon dan compact mobile context.
- Berikan clear space minimal setara tinggi huruf kecil pada wordmark di seluruh sisi logo.
- Jangan menambahkan shadow, glow, gradient, atau stroke baru pada logo.
- Jangan mengubah proporsi atau memutar logo.

### Background Pairing
| Background | Logo Treatment |
|---|---|
| Warm White `#FCFBF7` | Primary logo |
| White `#FFFFFF` | Primary logo |
| Forest Green `#153528` | Inverse/light logo |
| Photography | Gunakan logo inverse + overlay jika contrast tidak cukup |
| Champagne `#D8BD94` | Hindari sebagai background logo utama kecuali versi monochrome telah disiapkan |

---

## Design Tokens for Frontend

Recommended CSS variable mapping:

```css
:root {
  --color-primary: #153528;
  --color-primary-active: #0e2b20;
  --color-primary-disabled: #9baaa3;
  --color-accent: #d8bd94;

  --color-canvas: #fcfbf7;
  --color-surface-soft: #f4f6f2;
  --color-surface-card: #ffffff;
  --color-surface-strong: #e8ede9;

  --color-ink: #19352b;
  --color-body: #52615a;
  --color-muted: #7a8781;
  --color-muted-soft: #aab4af;

  --color-border: #dde4df;
  --color-border-soft: #e9eeeb;
  --color-border-strong: #b9c8c0;

  --color-error: #b42318;
  --color-error-hover: #912018;

  --radius-sm: 8px;
  --radius-md: 14px;
  --radius-lg: 20px;
  --radius-xl: 28px;
  --radius-full: 9999px;

  --container-max: 1280px;
  --section-space: 80px;
}
```

For Tailwind, map the same tokens into `theme.extend.colors` rather than scattering hex values across components.

---

## Known Gaps

- Hover state colors and exact interaction animations masih dapat diperinci saat implementasi UI.
- Loading/skeleton states belum memiliki visual token khusus.
- Map atau specialized location view belum didefinisikan.
- Form error states sudah memiliki base color, tetapi copy dan validation pattern per field belum ditentukan.
- Secondary palette untuk campaign/package khusus belum didefinisikan.
- Dark mode belum menjadi requirement; sistem ini diprioritaskan untuk light hospitality experience.
- Exact logo clear-space measurement belum dihitung dari vector source; gunakan proporsi visual logo sebagai baseline sampai asset SVG/vector tersedia.
- Final font licensing/hosting strategy perlu ditentukan sebelum production deployment.
