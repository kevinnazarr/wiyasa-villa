# FLOWCHART — Wiyasa Villa

**Document:** Business & Technical Flowchart  
**Version:** 1.1  
**Status:** Final Baseline  

Dokumen ini menggambarkan alur customer, reservation concurrency, payment, cancellation, admin manual booking, pricing/voucher, check-in, dan locale/i18n.

---

## 1. Customer Booking — End-to-End

```mermaid
flowchart TD
    A[Landing Page] --> A1[Resolve Locale: id / en]
    A1 --> B[Pilih Cabin]
    B --> C[Pilih Check-in]
    C --> D[Pilih Check-out]
    D --> E[Masukkan Jumlah Tamu]
    E --> F[Availability Check]

    F -->|Tidak tersedia| G[Tampilkan conflict + opsi tanggal/cabin lain]
    G --> B

    F -->|Tersedia| H[Calculate Quote]
    H --> I[Apply Voucher optional]
    I --> J[Review Booking]
    J --> K[POST Create Reservation / Hold]

    K -->|Conflict saat final transaction| G
    K -->|Hold berhasil| L[PENDING_PAYMENT]

    L --> M[Create Midtrans Payment]
    M --> N[Customer Payment]
    N --> O[Midtrans Notification/Webhook]
    O --> P[Verify Notification + Idempotency]
    P --> Q[Final Reservation Validation]

    Q -->|Valid + Hold masih aktif| R[CONFIRMED]
    Q -->|Hold expired / invalid| S[Reservation tetap EXPIRED]

    R --> T[Create Invoice]
    T --> U[Issue QR Check-in Token]
    U --> V[Booking Confirmation]

    S --> W[Refund / Reconciliation]
```

---

## 2. Availability Check vs Reservation Authority

Poin utama sistem:

```mermaid
flowchart LR
    A[Frontend Availability Check]
    B[Customer sees Available]
    C[Create Reservation Request]
    D[DB Transaction]
    E[Lock Cabin Row]
    F[Re-evaluate Availability]
    G{Conflict?}
    H[ROLLBACK]
    I[Create PENDING_PAYMENT]
    J[COMMIT]
    K[Proceed to Payment]

    A --> B --> C --> D --> E --> F --> G
    G -->|Yes| H --> L[Return Unavailable]
    G -->|No| I --> J --> K
```

**Kesimpulan:** hasil `Availability Check` tidak pernah menjadi lock. Lock terjadi hanya ketika backend membuat reservation.

---

## 3. Concurrent Booking — Customer A vs Customer B

Skenario: dua customer memilih cabin dan tanggal yang sama hampir bersamaan.

```mermaid
sequenceDiagram
    participant A as Customer A
    participant B as Customer B
    participant API as Laravel
    participant DB as PostgreSQL

    A->>API: POST /reservations
    B->>API: POST /reservations

    API->>DB: BEGIN + SELECT cabin FOR UPDATE
    DB-->>API: Cabin lock acquired by A
    API->>DB: Check active overlap
    DB-->>API: No conflict
    API->>DB: INSERT PENDING_PAYMENT
    API->>DB: COMMIT
    DB-->>API: Hold created
    API-->>A: HOLD + payment details

    API->>DB: SELECT cabin FOR UPDATE
    Note over API,DB: B waits for A's lock
    DB-->>API: Lock acquired by B after A commit
    API->>DB: Check active overlap
    DB-->>API: A's hold found
    API->>DB: ROLLBACK
    API-->>B: Unavailable / choose another date
```

### Invariant

```text
At most one active overlapping reservation per cabin.
```

---

## 4. Reservation Hold State

```mermaid
stateDiagram-v2
    [*] --> PENDING_PAYMENT

    PENDING_PAYMENT --> CONFIRMED: Valid payment webhook before hold expiry
    PENDING_PAYMENT --> EXPIRED: Hold timeout
    PENDING_PAYMENT --> EXPIRED: Payment failed/expired
    PENDING_PAYMENT --> CANCELLED: Allowed operational cancellation

    CONFIRMED --> CHECKED_IN: Successful QR verification
    CONFIRMED --> CANCELLED: Cancellation policy applied

    CHECKED_IN --> COMPLETED: Stay completed

    EXPIRED --> [*]
    CANCELLED --> [*]
    COMPLETED --> [*]
```

---

## 5. Hold Expiration

```mermaid
flowchart TD
    A[PENDING_PAYMENT exists] --> B{hold_expires_at > now?}
    B -->|Yes| C[Inventory remains blocked]
    B -->|No| D[Treat as stale hold]
    D --> E[Within next reservation transaction: expire hold]
    E --> F[Inventory becomes available]
    F --> G[Create EXPIRED state + audit if required]
```

Scheduler/queue digunakan untuk cleanup, tetapi availability correctness tidak bergantung pada scheduler tepat waktu.

---

## 6. Payment Webhook Flow

```mermaid
flowchart TD
    A[Midtrans Notification] --> B[Receive Webhook]
    B --> C[Identify Event / Transaction]
    C --> D{Already Processed?}
    D -->|Yes| E[Return Idempotent Success]
    D -->|No| F[Verify Provider Notification]
    F -->|Invalid| G[Reject / Log Failure]
    F -->|Valid| H[Load Reservation + Payment]
    H --> I[BEGIN TRANSACTION]
    I --> J[Lock Reservation/Cabin as needed]
    J --> K{Reservation Still Eligible?}
    K -->|No| L[Keep EXPIRED/CANCELLED + create Reconciliation/Refund Flow]
    K -->|Yes| M[Mark Payment PAID]
    M --> N[Set Reservation CONFIRMED]
    N --> O[Persist Webhook Processed]
    O --> P[COMMIT]
    P --> Q[Dispatch Invoice/QR/Notification Jobs]
```

---

## 7. Late Payment After Hold Expired

```mermaid
flowchart TD
    A[Payment SUCCESS arrives] --> B[Load Reservation]
    B --> C{Reservation hold still valid?}
    C -->|Yes| D[Final availability/payment validation]
    D --> E{No conflict + valid?}
    E -->|Yes| F[CONFIRMED]
    E -->|No| G[Reconciliation / Refund]

    C -->|No| H[Reservation EXPIRED]
    H --> I[Never auto-confirm]
    I --> J[Refund / Reconciliation]
    J --> K[Customer may create a new reservation]
```

---

## 8. Payment Failure

```mermaid
flowchart TD
    A[PENDING_PAYMENT] --> B[Customer Payment]
    B --> C{Payment Result}
    C -->|Success| D[Webhook success path]
    C -->|Failed| E[Mark Payment FAILED]
    E --> F[Release/Expire Hold]
    F --> G[Cabin Available]
    C -->|Timeout| H[Payment EXPIRED]
    H --> F
```

---

## 9. Pricing Calculation Flow

```mermaid
flowchart TD
    A[Check-in / Check-out] --> B[Build List of Stay Nights]
    B --> C[For Each Night]
    C --> D[Find Matching Pricing Periods]
    D --> E[Resolve Priority + Cabin Override + Day of Week]
    E --> F[Get Nightly Rate]
    F --> G[Calculate Extra Guest Fee]
    G --> H[Store Nightly Price Snapshot]
    H --> I{More Nights?}
    I -->|Yes| C
    I -->|No| J[Subtotal]
    J --> K[Apply Voucher]
    K --> L[Final Total]
    L --> M[Show Quote]
```

---

## 10. Voucher Concurrency Flow

```mermaid
flowchart TD
    A[Customer enters voucher] --> B[Validate Basic Rules]
    B --> C[BEGIN Reservation Transaction]
    C --> D[Lock / Reserve Voucher Quota]
    D --> E{Quota available?}
    E -->|No| F[Reject Voucher]
    E -->|Yes| G[Create Voucher Redemption RESERVED]
    G --> H[Create Reservation PENDING_PAYMENT]
    H --> I[COMMIT]
    I --> J[Customer Pays]
    J --> K{Payment Confirmed?}
    K -->|Yes| L[Redemption REDEEMED]
    K -->|No / Expired| M[Redemption RELEASED]
```

---

## 11. Admin Manual Booking

```mermaid
flowchart TD
    A[Admin opens Calendar] --> B[Select Cabin + Dates]
    B --> C[Check Availability]
    C -->|Conflict| D[Reject / Choose Another Slot]
    C -->|Available| E[Enter Customer Data]
    E --> F{Payment Method}
    F -->|Manual Payment Verified| G[Create Reservation CONFIRMED]
    F -->|Payment Later| H[Create PENDING_PAYMENT + Hold]
    G --> I[Generate Invoice]
    H --> J[Wait Payment]
    J --> K[Midtrans/Manual Payment Validation]
    K --> L[Confirm or Expire]

    G --> M[Audit Log]
    H --> M
    L --> M
```

**Important:** Admin manual booking menggunakan engine availability yang sama. Tidak boleh ada jalur `direct insert without validation`.

---

## 12. Cancellation Flow

```mermaid
flowchart TD
    A[Customer/Admin requests cancellation] --> B[Load Reservation]
    B --> C{Current status?}
    C -->|PENDING_PAYMENT| D[Expire/Cancel Hold]
    C -->|CONFIRMED| E[Evaluate Cancellation Policy]
    C -->|CHECKED_IN| F[Reject cancellation]
    C -->|COMPLETED| F
    C -->|CANCELLED/EXPIRED| G[No-op / Already terminal]

    E --> H[Calculate Refund Amount]
    H --> I[Set Reservation CANCELLED]
    I --> J[Create Refund if Amount > 0]
    J --> K[Audit Log]
    D --> K
```

---

## 13. QR Check-in Flow

```mermaid
flowchart TD
    A[Customer arrives] --> B[Admin scans QR]
    B --> C[Extract Opaque Token]
    C --> D[Hash/Lookup Token]
    D --> E{Token valid?}
    E -->|No| F[Reject]
    E -->|Yes| G[Load Reservation]
    G --> H{Reservation eligible?}
    H -->|No| F
    H -->|Yes| I{Already checked-in?}
    I -->|Yes| J[Reject duplicate check-in]
    I -->|No| K[Create Check-in Record]
    K --> L[Reservation CHECKED_IN]
    L --> M[Audit Log]
```

---

## 14. Availability Rules

### Requested range

```text
requested_start = check_in_date
requested_end   = check_out_date
```

### Existing booking overlaps if

```text
existing_start < requested_end
AND
existing_end > requested_start
```

### Active condition

```text
CONFIRMED
OR CHECKED_IN
OR (
    PENDING_PAYMENT
    AND hold_expires_at > now
)
```

### Additional blockers

```text
availability_blocks
```

juga dianggap unavailable.

---

## 15. State Machine Summary

```mermaid
flowchart LR
    A[PENDING_PAYMENT] -->|payment valid| B[CONFIRMED]
    A -->|timeout/payment failed| C[EXPIRED]
    A -->|allowed cancellation| D[CANCELLED]
    B -->|QR check-in| E[CHECKED_IN]
    B -->|policy cancellation| D
    E -->|stay finished| F[COMPLETED]
```

---

## 16. Background Jobs

Background processing yang direkomendasikan:

```text
ExpireStaleReservationsJob
ProcessMidtransNotificationJob
GenerateInvoiceJob
GenerateCheckInTokenJob
SendBookingConfirmationJob
SendPaymentFailureNotificationJob
ProcessRefundJob
CleanupTemporaryFilesJob
```

### Scheduler rule

Scheduler membantu cleanup, tetapi bukan sumber kebenaran availability.

Contoh:

```text
Every minute
→ Find stale PENDING_PAYMENT
→ Mark EXPIRED
→ Release voucher reservations
```

Jika scheduler terlambat, transaction booking berikutnya tetap harus dapat expire stale hold secara on-demand.

---

## 17. Operational Calendar Flow

```mermaid
flowchart TD
    A[Admin Calendar] --> B[Select Date Range]
    B --> C[Load 10 Cabin Inventory]
    C --> D[Merge Reservations]
    D --> E[Merge Active Holds]
    E --> F[Merge Availability Blocks]
    F --> G[Render Availability Grid]
    G --> H{Admin action}
    H -->|View Reservation| I[Reservation Detail]
    H -->|Manual Booking| J[Manual Booking Flow]
    H -->|Block Cabin| K[Availability Block Flow]
    H -->|Check-in| L[QR Check-in Flow]
```

---

## 18. Business Data Flow

```mermaid
flowchart LR
    A[Super Admin] --> B[Cabin Config]
    A --> C[Pricing Config]
    A --> D[Voucher Config]
    A --> E[Booking Policy]
    A --> F[Cancellation Policy]

    B --> G[Database]
    C --> G
    D --> G
    E --> G
    F --> G

    G --> H[Laravel Domain Services]
    H --> I[Customer Quote]
    H --> J[Availability]
    H --> K[Reservation]
```

Prinsipnya: business configuration dibaca dari database/configuration layer, bukan dari hardcoded frontend/backend constants.

---

## 19. Final Flow Principle

Wiyasa memiliki dua tahap yang berbeda:

```text
DISCOVERY
→ availability check
→ quote

COMMITMENT
→ transaction
→ cabin lock
→ re-check
→ hold
→ payment
→ final validation
→ confirmed
```

**Tidak ada flow yang boleh melompati tahap commitment dan langsung mengubah inventory.**
## 20. Internationalization / Locale Resolution Flow

```mermaid
flowchart TD
    A[Request masuk] --> B{Authenticated?}
    B -->|Yes| C[Load users.locale]
    B -->|No| D[Load session/cookie locale]
    C --> E{Locale valid?}
    D --> E
    E -->|id| F[Set application locale = id]
    E -->|en| G[Set application locale = en]
    E -->|Invalid / missing| H[Use default locale = id]

    F --> I[Load vue-i18n dictionary + Laravel translations]
    G --> I
    H --> I
    I --> J[Render localized UI / messages]

    J --> K{User changes language?}
    K -->|No| L[Continue request/session]
    K -->|Yes, guest| M[Persist locale in session/cookie]
    K -->|Yes, authenticated| N[Update users.locale + session/cookie]
    M --> L
    N --> L
```

### i18n rules

- Supported locales pada baseline: `id` dan `en`.
- Default locale: `id`. Fallback locale: `en`.
- UI string menggunakan translation key melalui `vue-i18n`.
- Validation/notification/server-side messages menggunakan Laravel localization.
- Dynamic cabin/facility content menggunakan translation tables sesuai locale aktif.
- `reservations.locale` menyimpan snapshot locale pada saat reservation dibuat.
- Perubahan locale tidak boleh mengubah availability, pricing, payment state, atau business rules.

---

