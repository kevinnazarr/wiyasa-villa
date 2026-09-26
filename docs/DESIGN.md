---
version: alpha
name: Wiyasa Villa Design System
description: Design system untuk Wiyasa Villa yang menekankan karakter hospitality premium yang tenang, natural, hangat, dan editorial. Visual menggunakan canvas terang, aksen earthy sebagai primary action, tipografi yang elegan namun tetap mudah dibaca, whitespace yang lapang, dan shape language soft-rounded tanpa berlebihan.

colors:
  primary: "#153528"
  primary-active: "#0F2A20"
  primary-disabled: "#8E9D95"
  primary-error-text: "#B42318"
  primary-error-text-hover: "#912018"
  secondary: "#D8BD94"
  accent: "#D8BD94"
  ink: "#17251E"
  body: "#46534C"
  muted: "#707C75"
  muted-soft: "#A8B1AC"
  hairline: "#E0E6E2"
  hairline-soft: "#EDF1EE"
  border-strong: "#BCC9C2"
  canvas: "#FAF8F2"
  surface-soft: "#F3EFE7"
  surface-card: "#FFFFFF"
  surface-strong: "#E4E9E5"
  on-primary: "#F3EBD9"
  on-dark: "#F3EBD9"
  legal-link: "#153528"
  star-rating: "#D8BD94"
  scrim: "#0B2118"

typography:
  display-xl:
    fontFamily: "'DM Sans', Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: 48px
    fontWeight: 700
    lineHeight: 1.08
    letterSpacing: -0.96px
  display-lg:
    fontFamily: "'DM Sans', Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: 40px
    fontWeight: 700
    lineHeight: 1.12
    letterSpacing: -0.72px
  display-md:
    fontFamily: "'DM Sans', Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: 32px
    fontWeight: 700
    lineHeight: 1.18
    letterSpacing: -0.48px
  display-sm:
    fontFamily: "'DM Sans', Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: 26px
    fontWeight: 600
    lineHeight: 1.22
    letterSpacing: -0.26px
  title-md:
    fontFamily: "'DM Sans', Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: 20px
    fontWeight: 600
    lineHeight: 1.3
    letterSpacing: -0.1px
  title-sm:
    fontFamily: "'DM Sans', Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: 18px
    fontWeight: 600
    lineHeight: 1.3
    letterSpacing: 0
  body-md:
    fontFamily: "'DM Sans', Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: 16px
    fontWeight: 400
    lineHeight: 1.5
    letterSpacing: 0
  body-sm:
    fontFamily: "'DM Sans', Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: 14px
    fontWeight: 400
    lineHeight: 1.45
    letterSpacing: 0
  caption:
    fontFamily: "'DM Sans', Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: 14px
    fontWeight: 500
    lineHeight: 1.35
    letterSpacing: 0
  caption-sm:
    fontFamily: "'DM Sans', Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: 13px
    fontWeight: 400
    lineHeight: 1.35
    letterSpacing: 0
  badge:
    fontFamily: "'DM Sans', Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: 11px
    fontWeight: 600
    lineHeight: 1.2
    letterSpacing: 0
  micro-label:
    fontFamily: "'DM Sans', Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: 12px
    fontWeight: 700
    lineHeight: 1.3
    letterSpacing: 0.02px
  uppercase-tag:
    fontFamily: "'DM Sans', Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: 10px
    fontWeight: 700
    lineHeight: 1.2
    letterSpacing: 0.48px
    textTransform: uppercase
  button-md:
    fontFamily: "'DM Sans', Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: 15px
    fontWeight: 600
    lineHeight: 1.25
    letterSpacing: 0
  button-sm:
    fontFamily: "'DM Sans', Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: 14px
    fontWeight: 600
    lineHeight: 1.3
    letterSpacing: 0
  link:
    fontFamily: "'DM Sans', Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: 14px
    fontWeight: 500
    lineHeight: 1.4
    letterSpacing: 0
  nav-link:
    fontFamily: "'DM Sans', Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: 15px
    fontWeight: 600
    lineHeight: 1.25
    letterSpacing: 0

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
  section: 64px

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
    textColor: "{colors.ink}"
    typography: "{typography.button-md}"
    rounded: "{rounded.sm}"
    padding: 13px 23px
    height: 48px
  button-tertiary-text:
    backgroundColor: transparent
    textColor: "{colors.ink}"
    typography: "{typography.button-md}"
  button-pill:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.on-primary}"
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
  top-nav:
    backgroundColor: "{colors.canvas}"
    textColor: "{colors.ink}"
    typography: "{typography.nav-link}"
    height: 72px
  product-tab-active:
    backgroundColor: transparent
    textColor: "{colors.ink}"
    typography: "{typography.nav-link}"
    rounded: "{rounded.none}"
  product-tab-inactive:
    backgroundColor: transparent
    textColor: "{colors.muted}"
    typography: "{typography.nav-link}"
  new-tag:
    backgroundColor: "{colors.surface-soft}"
    textColor: "{colors.ink}"
    typography: "{typography.uppercase-tag}"
    rounded: "{rounded.full}"
    padding: 4px 8px
  search-bar-pill:
    backgroundColor: "{colors.canvas}"
    textColor: "{colors.ink}"
    typography: "{typography.body-sm}"
    rounded: "{rounded.full}"
    padding: 14px 20px
    height: 60px
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
    textColor: "{colors.ink}"
    typography: "{typography.button-sm}"
    rounded: "{rounded.none}"
  card:
    backgroundColor: "{colors.surface-card}"
    textColor: "{colors.ink}"
    typography: "{typography.body-sm}"
    rounded: "{rounded.md}"
  card-photo:
    rounded: "{rounded.md}"
  badge:
    backgroundColor: "{colors.canvas}"
    textColor: "{colors.ink}"
    typography: "{typography.badge}"
    rounded: "{rounded.full}"
    padding: 4px 10px
  text-input:
    backgroundColor: "{colors.canvas}"
    textColor: "{colors.ink}"
    typography: "{typography.body-md}"
    rounded: "{rounded.sm}"
    padding: 14px 12px
    height: 52px
  footer-light:
    backgroundColor: "{colors.surface-soft}"
    textColor: "{colors.ink}"
    typography: "{typography.body-sm}"
    padding: 48px 80px
  footer-link:
    backgroundColor: transparent
    textColor: "{colors.ink}"
    typography: "{typography.body-sm}"
  legal-band:
    backgroundColor: "{colors.canvas}"
    textColor: "{colors.muted}"
    typography: "{typography.caption-sm}"
---

# DESIGN.md — Wiyasa Villa Design System

## Overview

Wiyasa Villa menggunakan bahasa visual hospitality yang **natural, premium, tenang, dan hangat**. Sistem desain memprioritaskan fotografi villa sebagai visual utama, whitespace yang cukup, tipografi yang bersih, dan aksen earthy untuk CTA serta elemen interaktif penting.

Base canvas menggunakan putih dengan surface lembut bernuansa warm-neutral. Primary menggunakan deep forest green yang diambil dari warna dominan logo, sehingga interface konsisten dengan identitas visual Wiyasa Villa dan konteks alam Dieng. Shape language menggunakan rounded corner yang moderat; komponen tidak menggunakan radius ekstrem kecuali elemen pill dan circular control.

Design system ini berlaku terutama untuk `apps/web/`. Token di bagian awal dokumen menjadi referensi implementasi dan dapat dipetakan ke CSS variables/Tailwind theme ketika frontend design token layer dibuat.

### Key Characteristics

- **Warna utama:** deep forest green untuk CTA dan interaction utama; neutral warm untuk surface.
- **Tipografi:** sans-serif modern dengan hierarchy kuat dan readability tinggi.
- **Navigation:** sederhana, ringan, dan tidak mengambil perhatian dari konten hospitality.
- **Search/input:** field memiliki surface bersih, radius soft, dan target interaksi minimum 48px.
- **Card:** foto menjadi visual dominan; metadata dibuat ringkas dan mudah dipindai.
- **Elevation:** flat-first; shadow digunakan secara selektif, bukan sebagai dekorasi utama.
- **Spacing:** generous whitespace untuk memberikan kesan premium dan menjaga fokus pada foto/content.

## Brand Color Source

Warna brand pada DESIGN.md diselaraskan dengan logo Wiyasa Villa yang akan digunakan sebagai primary brand asset.

- **Forest Green — Primary:** `#153528` — warna hijau gelap dominan pada favicon/logo.
- **Forest Green Active:** `#0F2A20` — turunan lebih gelap untuk pressed/active state.
- **Warm Cream — On Primary:** `#F3EBD9` — warna cream terang pada artwork/logo dan digunakan sebagai foreground di atas primary.
- **Champagne Gold — Secondary/Accent:** `#D8BD94` — warna aksen matahari/ornamen pada logo.
- **Soft Green — Disabled:** `#8E9D95` — turunan desaturasi untuk disabled state.

Primary brand direction karena itu adalah **forest green + warm cream + champagne gold**, bukan earthy brown. Token warna lain harus mendukung kombinasi tersebut dan tidak memperkenalkan hue brand baru tanpa alasan desain.

## Colors

### Brand & Accent

- **Primary** (`{colors.primary}` — `#153528`): CTA utama, booking action, accent interaktif.
- **Primary Active** (`{colors.primary-active}` — `#0F2A20`): hover/pressed state.
- **Primary Disabled** (`{colors.primary-disabled}` — `#8E9D95`): disabled state.
- **Secondary** (`{colors.secondary}` — `#D8BD94`): secondary accent yang masih berada dalam keluarga earthy.
- **Accent** (`{colors.accent}` — `#D8BD94`): highlight ringan, decorative accent, atau emphasis non-critical.

### Surface

- **Canvas** (`{colors.canvas}` — `#FFFFFF`): background default.
- **Surface Soft** (`{colors.surface-soft}` — `#F3EFE7`): section alternatif, footer, subtle panel.
- **Surface Card** (`{colors.surface-card}` — `#FFFFFF`): card utama.
- **Surface Strong** (`{colors.surface-strong}` — `#E4E9E5`): icon button, secondary emphasis, subtle control surface.

### Hairlines & Borders

- **Hairline** (`{colors.hairline}` — `#E0E6E2`): border default.
- **Hairline Soft** (`{colors.hairline-soft}` — `#EDF1EE`): divider yang lebih soft.
- **Border Strong** (`{colors.border-strong}` — `#BCC9C2`): field/error/focus boundary yang membutuhkan separation lebih kuat.

### Text

- **Ink** (`{colors.ink}` — `#17251E`): headline, nav, primary text.
- **Body** (`{colors.body}` — `#46534C`): body text.
- **Muted** (`{colors.muted}` — `#707C75`): secondary/inactive text.
- **Muted Soft** (`{colors.muted-soft}` — `#A8B1AC`): disabled/subtle text.
- **On Primary** (`{colors.on-primary}` — `#FFFFFF`): text di atas primary.

### Semantic

- **Error** (`{colors.primary-error-text}` — `#B42318`): error text dan destructive validation message.
- **Error Hover** (`{colors.primary-error-text-hover}` — `#912018`): hover/active error interaction.
- **Legal Link** (`{colors.legal-link}` — `#0F2A20`): legal/privacy/terms link.

### Scrim

- **Scrim** (`{colors.scrim}` — `#0B2118` at 50% opacity): modal/backdrop.

## Typography

### Font Family

Sistem menggunakan **DM Sans** untuk display, body, navigation, form, dan utility text.

Fallback:

```text
Inter, ui-sans-serif, system-ui, sans-serif
```

DM Sans dipilih sebagai baseline karena interface membutuhkan karakter modern dan hospitality yang bersih tanpa mengorbankan readability.

### Hierarchy

| Token | Size | Weight | Line Height | Letter Spacing | Use |
|---|---:|---:|---:|---:|---|
| `display-xl` | 48px | 700 | 1.08 | -0.96px | Hero/display utama |
| `display-lg` | 40px | 700 | 1.12 | -0.72px | Section headline besar |
| `display-md` | 32px | 700 | 1.18 | -0.48px | Page/section heading |
| `display-sm` | 26px | 600 | 1.22 | -0.26px | Sub-section heading |
| `title-md` | 20px | 600 | 1.30 | -0.10px | Card/title utama |
| `title-sm` | 18px | 600 | 1.30 | 0 | Supporting title |
| `body-md` | 16px | 400 | 1.50 | 0 | Body/default content |
| `body-sm` | 14px | 400 | 1.45 | 0 | Secondary content |
| `caption` | 14px | 500 | 1.35 | 0 | Metadata/label |
| `caption-sm` | 13px | 400 | 1.35 | 0 | Fine print |
| `badge` | 11px | 600 | 1.20 | 0 | Badge |
| `button-md` | 15px | 600 | 1.25 | 0 | Primary CTA |
| `button-sm` | 14px | 600 | 1.30 | 0 | Compact CTA |
| `nav-link` | 15px | 600 | 1.25 | 0 | Navigation |

### Principles

- Display typography boleh kuat tetapi tidak terlalu condensed.
- Heading menggunakan weight 600–700 untuk hierarchy.
- Body menggunakan 400 untuk readability.
- CTA menggunakan 600 agar action mudah dikenali.
- Hindari terlalu banyak uppercase text.
- Gunakan whitespace dan photography untuk menciptakan premium feel, bukan font size ekstrem.
- Jangan menggunakan terlalu banyak font family dalam satu interface.

### Note on Font Substitutes

Jika DM Sans belum tersedia, gunakan **Inter** sebagai substitute utama.

Fallback terakhir:

```text
ui-sans-serif, system-ui, sans-serif
```

## Layout

### Spacing System

- **Base unit:** 4px.
- **Tokens:** `xxs` 2px · `xs` 4px · `sm` 8px · `md` 12px · `base` 16px · `lg` 24px · `xl` 32px · `xxl` 48px · `section` 64px.
- **Section padding:** 64px sebagai baseline desktop.
- **Card internal padding:** 16px untuk content umum; 20–24px untuk card yang lebih editorial.
- **Gutters:** 16–24px mobile/tablet dan 24–32px desktop sesuai layout.

### Grid & Container

- **Max content width:** 1280px.
- **Desktop content:** centered container dengan responsive side gutters.
- **Cabin/property listing:** responsive card grid, satu kolom di mobile dan multi-column pada viewport lebih lebar.
- **Detail page:** dapat menggunakan layout 2-column pada desktop jika kebutuhan content mengharuskannya.
- **Footer:** multi-column pada desktop, stacked pada mobile.

### Whitespace Philosophy

Wiyasa Villa menggunakan **editorial hospitality density**: tidak terlalu padat seperti dashboard enterprise, tetapi juga tidak terlalu kosong sampai menyulitkan scanning.

Gunakan whitespace untuk:

- memberi ruang pada photography
- memisahkan section
- menonjolkan CTA
- memperjelas hierarchy
- membuat booking flow terasa tenang dan tidak terburu-buru

## Elevation

Sistem menggunakan **flat-first elevation**.

- **Flat:** mayoritas page surface, hero, section, footer, dan card.
- **Card hover / elevated:** gunakan shadow ringan hanya ketika interaction membutuhkan feedback.
- **Modal scrim:** `{colors.scrim}` dengan opacity sekitar 50%.
- Jangan menggunakan shadow besar secara default.
- Jangan menggunakan elevation sebagai pengganti hierarchy yang seharusnya dibuat melalui spacing, typography, dan surface color.

## Components

### Buttons

**`button-primary`** — CTA utama dengan deep forest green fill, white text, radius 8px, tinggi 48px. Digunakan untuk aksi utama seperti booking.

**`button-primary-active`** — state pressed/active menggunakan primary yang lebih gelap.

**`button-primary-disabled`** — menggunakan primary-disabled dengan contrast yang tetap cukup untuk menunjukkan disabled state.

**`button-secondary`** — surface putih, text ink, border dapat menggunakan hairline/border-strong. Digunakan untuk secondary action.

**`button-tertiary-text`** — text-only action tanpa surface. Digunakan untuk secondary navigation, dismiss, atau action non-critical.

**`button-pill`** — CTA pill untuk context yang membutuhkan visual lebih compact atau promotional emphasis.

### Search Surface

**`search-bar-pill`** — search/availability surface utama dengan radius full, tinggi 60px, background putih, dan pemisahan field yang halus.

**`search-orb`** — circular primary action yang digunakan pada ujung search/availability bar jika pola tersebut diperlukan.

### Top Navigation

**Primary navigation:** `Home` · `Stay` · `Gallery` · `About`.

**Primary CTA:** `Book Your Stay` → `/booking`. Booking is treated as the primary business action rather than a regular navigation tab.

The navigation should remain visually lightweight so the brand photography and booking CTA remain the dominant elements.

**`top-nav`** — navigation utama dengan tinggi 72px, canvas putih, dan typography nav-link.

**`product-tab-active` / `product-tab-inactive`** — active state menggunakan ink; inactive menggunakan muted. Hindari active state yang terlalu dekoratif.

**`new-tag`** — badge kecil untuk informasi fitur atau content baru.

### Cards

**`card`** — container utama untuk cabin/property content. Gunakan background putih dan radius 14px.

**`card-photo`** — image plate dengan radius mengikuti card. Foto harus menjadi elemen visual utama.

**`badge`** — informasi singkat seperti status, category, atau highlight. Gunakan pill shape hanya ketika kontennya pendek.

### Forms

**`text-input`** — tinggi baseline 52px, radius 8px, padding horizontal 12px, text ink, background putih.

Focus state harus terlihat jelas melalui border/focus ring dan tidak hanya mengandalkan perubahan warna yang subtle.

Untuk booking form, input touch target minimum tetap mengikuti responsive/touch-target rules.

### Footer

**`footer-light`** — surface soft, padding besar, dan struktur kolom yang sederhana.

**`legal-band`** — area legal/copyright menggunakan typography kecil dan muted text.

## Responsive Behavior

| Name | Width | Key Changes |
|---|---|---|
| Mobile | < 744px | Navigation collapse, content 1-up, search stack/collapse, CTA touch-friendly |
| Tablet | 744–1128px | Grid bertambah menjadi 2-up bila ruang cukup, navigation lebih ringkas |
| Desktop | 1128–1440px | Full navigation, multi-column content, max container 1280px |
| Wide | > 1440px | Content width tetap capped, tambahan viewport menjadi outer gutter |

### Touch Targets

- Primary CTA minimum `48×48px`.
- Icon button minimum `40×40px`, ideal `44×44px` untuk control yang sering digunakan.
- Interactive date/cabin cells harus menyediakan area sentuh yang cukup dan tidak terlalu rapat.
- Jangan membuat text-only interactive target yang terlalu kecil pada mobile.

### Collapsing Strategy

- Desktop navigation menjadi compact/mobile navigation pada viewport kecil.
- Search/availability bar dapat berubah dari horizontal menjadi stacked.
- Card grid menjadi 1-up pada mobile.
- Multi-column footer menjadi stacked.
- Sticky mobile CTA hanya digunakan ketika benar-benar membantu conversion/booking flow dan tidak menutupi content.

## Design Rules for Wiyasa Villa

### Photography

Photography merupakan bagian penting dari visual identity.

- Gunakan aspect ratio konsisten per context.
- Hindari crop yang memotong focal point utama tanpa alasan.
- Gunakan overlay hanya ketika diperlukan untuk readability.
- Jangan menggunakan placeholder image sebagai final production visual.

### Booking CTA

Booking merupakan primary business action.

CTA booking harus:

- memiliki primary treatment yang konsisten
- mudah ditemukan
- memiliki touch target yang cukup
- menunjukkan loading/disabled state
- tidak mengklaim availability sebelum backend mengonfirmasi availability

### Availability UI

Availability adalah data server-authoritative.

UI boleh memberikan loading/skeleton dan optimistic visual feedback terbatas, tetapi tidak boleh menganggap cabin tersedia hanya karena state frontend.

### Status Colors

Status operational/booking/payment harus menggunakan semantic colors yang jelas.

Jangan mengandalkan warna saja untuk menyampaikan status; gunakan label/icon/text yang sesuai.

### Accessibility

- Text/background harus memenuhi contrast yang layak.
- Focus state harus terlihat.
- Interactive controls harus keyboard accessible.
- Jangan menggunakan warna sebagai satu-satunya penanda error/success.
- Image penting harus memiliki alt text yang relevan.
- Motion harus minimal dan tidak mengganggu task utama.

## Responsive Content Priority

Pada mobile, prioritas informasi:

```text
1. Villa / cabin identity
2. Availability / booking CTA
3. Price / stay information
4. Key facilities
5. Supporting description
6. Secondary information
```

Jangan menyembunyikan informasi penting hanya karena viewport kecil; reorganize sebelum remove.

## Known Gaps

Dokumen ini merupakan baseline design system dan masih memiliki beberapa keputusan yang perlu divalidasi ketika visual implementation dimulai:

- Final brand color perlu divalidasi terhadap logo `docs/assets/wiyasa-villa-primary-logo.png`.
- Final font loading/source belum ditentukan.
- Final photography art direction belum terdokumentasi.
- Detailed loading/skeleton states belum dibuat.
- Complete form error/success states belum dibuat.
- Date picker/availability calendar belum memiliki token khusus.
- Map/location component belum didefinisikan.
- Admin/receptionist interface mungkin membutuhkan density yang berbeda dari customer-facing UI.
- Dark mode belum menjadi scope.
- Final semantic status palette untuk booking/payment/cabin operational state perlu disesuaikan dengan UI implementation.

## Implementation Guidance

Design tokens di bagian YAML adalah **source of design intent**, bukan alasan untuk membuat styling abstraction yang berlebihan.

Implementasi frontend sebaiknya:

1. memetakan token ke CSS variables/theme;
2. membuat primitive yang reusable hanya ketika benar-benar digunakan lebih dari satu context;
3. menghindari hardcoded color/radius/spacing yang bertentangan dengan token;
4. menjaga accessibility pada component state;
5. memastikan responsive behavior menjadi bagian dari component design, bukan patch terakhir.

## Status

**Alpha / Proposed**

Design system ini menjadi baseline visual Wiyasa Villa. Nilai token dapat disesuaikan setelah visual identity, logo, photography, dan implementasi landing page pertama divalidasi.
