<p align="center">
  <img src="assets/wiyasa-villa-primary-logo.webp" alt="Wiyasa Villa" width="720">
</p>

<p align="center">
  Premium private cabin stay in Dieng, Wonosobo.
</p>

---

## Overview

Wiyasa Villa adalah project full-stack booking system untuk bisnis private cabin di kawasan Dieng, Wonosobo.

Project menggabungkan:

- landing page untuk marketing cabin;
- cabin catalog dan availability;
- reservation dan temporary hold;
- payment melalui Midtrans;
- invoice;
- QR verification untuk check-in;
- customer management;
- operational admin dashboard;
- Super Admin configuration.

Wiyasa dirancang sebagai **data-driven booking platform**. Data cabin, kapasitas, fasilitas, pricing, season, voucher, booking policy, hold duration, dan cancellation policy tidak boleh bergantung pada hardcode business data.

## Architecture

Wiyasa menggunakan **Laravel monolith + Inertia.js + Vue 3**.

```text
Browser
   │
   ▼
Laravel + Inertia
   │
   ├── Vue 3 + TypeScript + Vite
   │
   ├── Business / Domain Logic
   │
   ├── PostgreSQL
   │
   ├── Redis
   │
   ├── Midtrans
   │
   └── Cloudflare R2
```

Pada current architecture, repository hanya memiliki:

```text
apps/
└── web/
```

Tidak ada `apps/api` terpisah.

## Tech Stack

### Application

- Laravel 13
- PHP 8.5+
- Inertia.js
- Vue 3
- TypeScript
- Vite
- Tailwind CSS
- shadcn-vue / Reka UI
- Pinia
- TanStack Vue Query

### Backend Infrastructure

- PostgreSQL
- Redis
- Laravel Horizon
- Laravel Fortify
- Laravel authorization / policies
- Laravel queues

### External Services

- Midtrans — payment gateway
- Cloudflare R2 — object storage

### Development

- Docker Compose
- PostgreSQL container
- Redis container
- Mailpit
- pgAdmin Desktop
- npm workspaces

## Repository Structure

```text
wiyasa-villa/
├── .github/
│   ├── workflows/
│   ├── ISSUE_TEMPLATE/
│   ├── CODEOWNERS
│   └── PULL_REQUEST_TEMPLATE.md
│
├── apps/
│   └── web/
│
├── packages/
│
├── assets/
│   └── brand/
│       └── wiyasa-villa-primary-logo.webp
│
├── docs/
│   ├── PRD.md
│   ├── ERD.md
│   ├── FLOWCHART.md
│   ├── ARCHITECTURE.md
│   ├── log/
│   └── memory/
│
├── infrastructure/
│   └── docker/
│
├── test/
│
├── AGENTS.md
├── README.md
├── docker-compose.yml
├── package.json
└── package-lock.json
```

## Documentation

Dokumen utama:

- [PRD](docs/PRD.md)
- [ERD](docs/ERD.md)
- [Flowchart](docs/FLOWCHART.md)
- [Architecture](docs/ARCHITECTURE.md)

Project development juga mengikuti:

- [AGENTS.md](AGENTS.md)
- `docs/log/` untuk change log
- `docs/memory/` untuk project notes
- Obsidian Vault untuk project memory
- Code-Base-Memory MCP untuk codebase memory

## Local Development

### 1. Start infrastructure

Dari repository root:

```bash
docker compose up -d
```

Service development:

```text
PostgreSQL   127.0.0.1:55432
Redis        127.0.0.1:56379
Mailpit SMTP 127.0.0.1:58025
Mailpit UI   http://localhost:58081
pgAdmin      Desktop application
```

### 2. Start Laravel + Vue

```bash
cd apps/web
composer run dev
```

Aplikasi:

```text
http://localhost:8000
```

### 3. Run migrations

```bash
cd apps/web
php artisan migrate
```

### 4. Run tests

```bash
cd apps/web
php artisan test
```

### 5. Frontend build

Dari repository root:

```bash
npm run build --workspace apps/web
```

## Booking Consistency

Booking Wiyasa memiliki invariant utama:

> Satu cabin tidak boleh mempunyai lebih dari satu active reservation dengan interval menginap yang overlap.

Temporary hold:

```text
PENDING_PAYMENT
       │
       ├── payment success → CONFIRMED
       ├── payment failed → EXPIRED
       └── hold timeout    → EXPIRED
```

Pembuatan hold menggunakan:

```text
PostgreSQL transaction
+
row lock pada cabin
+
availability re-check
+
idempotency
```

Availability check dari frontend bukan jaminan reservation.

## Project Workflow

Perubahan code harus mengikuti workflow:

```text
Read AGENTS.md
      ↓
Read relevant docs
      ↓
Plan
      ↓
Implement
      ↓
Test
      ↓
Verify
      ↓
Create docs/log/LOG-ID.md
      ↓
Update relevant memory
      ↓
Final verification
```

Task tidak dianggap selesai hanya karena code sudah ditulis.

## Brand Assets

Brand asset canonical disimpan di:

```text
assets/brand/
```

Logo utama:

```text
assets/brand/wiyasa-villa-primary-logo.webp
```

Untuk asset yang benar-benar digunakan oleh aplikasi web, file dapat di-copy/optimized ke:

```text
apps/web/public/brand/
```

Contoh:

```text
apps/web/public/brand/wiyasa-villa-primary-logo.webp
```

Dengan pemisahan ini:

- `assets/brand/` menjadi source/canonical brand assets repository;
- `apps/web/public/brand/` menjadi web-serving assets.

Favicon sebaiknya menggunakan **brand mark berbentuk square**, bukan full horizontal wordmark. File favicon dapat ditempatkan di:

```text
apps/web/public/
```

atau:

```text
apps/web/public/brand/
```

## License

Project ini merupakan project portfolio / conceptual business system dan belum menetapkan lisensi open-source publik.

## Status

Foundation project sudah mencakup:

- Laravel + Inertia + Vue;
- PostgreSQL;
- Redis;
- Docker development infrastructure;
- authentication starter kit;
- project documentation baseline;
- agent governance.

Feature development mengikuti roadmap dan business rules pada dokumentasi project.
