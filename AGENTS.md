# AGENTS.md

Behavioral guidelines and project execution rules for AI coding agents working on **Wiyasa Villa**.

These instructions extend the project's engineering workflow and are intended to reduce common LLM coding mistakes, preserve project context, and make every code-changing task traceable and verifiable.

**Tradeoff:** These guidelines bias toward caution, traceability, and correctness over speed. For trivial read-only tasks, use judgment.

---

## 1. Think Before Coding

**Don't assume. Don't hide confusion. Surface tradeoffs.**

Before implementing:

- State assumptions explicitly.
- If uncertain, inspect the repository, project docs, or available tools before guessing.
- If multiple interpretations exist, present them and choose only after the ambiguity is resolved or the safest interpretation is clear.
- If a simpler approach exists, say so and prefer it unless there is a concrete reason not to.
- Do not introduce architecture, abstractions, dependencies, or features that were not requested.
- Treat the following as authoritative project context:
  - `docs/PRD.md`
  - `docs/ERD.md`
  - `docs/FLOWCHART.md`
  - this `AGENTS.md`
- If documentation and existing code disagree, do not silently choose one. Surface the discrepancy and resolve it deliberately.
- Never claim a tool, memory system, test, deployment, or verification step was used when it was not actually used.

For multi-step tasks, state a brief plan before implementation:

```text
1. [Step] → verify: [check]
2. [Step] → verify: [check]
3. [Step] → verify: [check]
```

---

## 2. Simplicity First

**Minimum code that correctly solves the problem. Nothing speculative.**

- No features beyond what was requested.
- No abstractions for single-use code unless they materially improve correctness or maintainability.
- No speculative "future-proofing" without a concrete current requirement.
- No unnecessary dependencies.
- No duplicate business rules in multiple layers.
- Prefer existing project conventions over introducing new ones.
- If a solution can be made materially smaller without reducing correctness, simplify it.
- Ask: **"Would a senior engineer consider this over-engineered for the current requirement?"**

If yes, simplify.

---

## 3. Surgical Changes

**Touch only what you must. Clean up only your own mess.**

When editing existing code:

- Do not "improve" unrelated code.
- Do not refactor adjacent code that is not part of the task.
- Match the existing style unless it conflicts with this project's explicit conventions.
- If unrelated dead code is discovered, mention it; do not delete it unless requested.
- Remove imports, variables, functions, or files that became unused because of your own changes.
- Every changed line should be traceable to:
  1. the user's request,
  2. a documented project requirement, or
  3. a necessary correction discovered during verification.

---

## 4. Goal-Driven Execution

**Define success criteria. Implement until verified.**

Transform tasks into verifiable goals:

- "Add validation" → write tests for invalid inputs, then make them pass.
- "Fix the bug" → reproduce with a test, fix it, then run the regression test.
- "Refactor X" → establish tests before the refactor, then verify behavior after it.
- "Add booking concurrency protection" → test concurrent/conflicting reservations and verify only valid holds survive.

Do not stop at "the code looks correct."

A task is not complete until its intended behavior has been verified.

---

## 5. Wiyasa Villa Project Context

Wiyasa Villa is a **premium cabin-stay booking platform for Dieng, Wonosobo**.

Current business baseline:

- 10 cabin units initially.
- Maximum 7 guests per cabin.
- Entire-cabin rental.
- Booking is per night.
- Baseline check-in: 14:00.
- Baseline check-out: 11:00.
- Baseline nightly pricing range: Rp900.000–Rp1.500.000.
- Pricing varies by weekday, weekend, peak season, and configurable special periods.
- Base occupancy and extra-guest charges are configurable.
- Voucher and discount rules are configurable.
- Temporary payment hold is configurable; current baseline is 15 minutes.
- `PENDING_PAYMENT` with an active hold blocks inventory.
- `CONFIRMED` booking blocks inventory.
- Expired/cancelled reservations no longer block inventory.
- Double booking prevention relies on PostgreSQL transaction boundaries, pessimistic locking, and a final overlap check.
- Payment confirmation is webhook-driven and idempotent.
- A successful payment after an already-expired hold must not automatically confirm that reservation.
- Invoice values are transactional snapshots and must not change retroactively.
- QR code is used for check-in verification.
- Admin can perform operational/manual bookings but may not bypass the reservation consistency rules.
- Super Admin manages system/business configuration but should not bypass inventory consistency.
- Cabin data, pricing, facilities, policies, voucher rules, hold duration, and operational configuration are **data-driven**, not hardcoded.

When implementation changes a business rule, update the project documentation deliberately rather than silently diverging from it.

---

## 6. Project Architecture

### Backend

- Laravel 13.x
- PHP 8.x
- Eloquent ORM
- Laravel Form Requests
- Laravel API/JSON Resources where appropriate
- Laravel Policies / Gates
- Laravel Jobs / Events / Listeners
- Laravel Horizon
- Laravel Sanctum where authentication architecture requires it

### Frontend

- Inertia.js
- Vue 3
- TypeScript
- Vite
- Pinia for client-only state
- TanStack Vue Query selectively for server-state workflows that benefit from client caching/refetching
- Tailwind CSS
- Reusable UI components

### Infrastructure

- PostgreSQL as source of truth
- Redis for cache, queues, and related transient infrastructure concerns
- Midtrans for payment processing
- Cloudflare R2 for object storage
- Docker / Docker Compose for local development and service orchestration
- pgAdmin 4 for development database inspection/management
- Mailpit for local email development/testing

### Monorepo

The project uses a monorepo structure.

Prefer a structure similar to:

```text
apps/
  web/
packages/
  ui/
  types/
  eslint-config/
infrastructure/
docs/
test/
```

Do not create extra applications/packages merely to make the repository "look like" a monorepo. Each package must have a concrete purpose.

The Laravel + Inertia + Vue application remains a single application unless the project requirements explicitly justify splitting it.

---

## 7. Source of Truth and Business Logic

**Business-critical rules belong on the server.**

Frontend code may improve UX, but it must never be the final authority for:

- availability
- reservation conflict detection
- cabin capacity
- pricing
- discounts
- voucher validity
- hold expiry
- payment state
- refund amount
- cancellation eligibility
- booking confirmation

Examples:

```text
Availability check in Vue
    ≠
authoritative availability decision
```

```text
Displayed price in Vue
    ≠
trusted final transaction amount
```

The backend must recalculate and validate all critical values before creating or confirming a reservation.

---

## 8. Data-Driven Configuration Rule

Do **not** hardcode business data such as:

- number of cabins
- cabin names/codes
- cabin capacity
- facilities
- cabin descriptions
- image references
- weekday/weekend prices
- peak-season prices
- minimum stay
- extra-guest fee
- hold duration
- cancellation policy
- voucher rules
- check-in/check-out policy
- operational blocks

These must be represented as database records or explicit application configuration where appropriate.

Examples of unacceptable implementation:

```ts
if (cabinId === 1) price = 900000
```

```php
if ($guestCount > 7) {
    throw ...
}
```

when the value is intended to be editable business data.

The correct pattern is:

```text
Business rule/data
    ↓
Database / configuration
    ↓
Domain/service logic
    ↓
UI
```

System-level constants and stable state identifiers may remain code-defined when they are truly part of application semantics.

---

## 9. Booking and Double-Booking Rules

The booking system must preserve this invariant:

> A cabin may not have two active overlapping reservations.

At the point of creating a hold/reservation:

1. Start a database transaction.
2. Lock the canonical cabin row/resource.
3. Re-check overlapping active reservations.
4. Re-check relevant blocking periods where applicable.
5. Validate capacity and business rules.
6. Calculate the authoritative price.
7. Reserve voucher quota if applicable.
8. Create the reservation/hold.
9. Commit the transaction.

Do not hold a database lock while waiting for a customer to complete payment.

Payment happens after the hold transaction commits.

Payment webhook handling must use its own transaction and must revalidate:

- reservation state
- hold expiry
- payment authenticity/status
- booking conflict/inventory state
- idempotency

Any shortcut that can create double booking is unacceptable, even if it appears faster.

---

## 10. Payment and Idempotency

Midtrans webhook processing must be idempotent.

Repeated notifications for the same payment event must not create:

- duplicate reservations
- duplicate confirmations
- duplicate invoices
- duplicate refunds
- duplicate voucher redemptions

External provider references should be treated as idempotency keys where appropriate.

A redirect from the payment gateway back to the browser is not by itself authoritative proof of payment success.

The server-side verified payment notification is authoritative.

---

## 11. Transaction Snapshots

Once a reservation has an authoritative transaction snapshot, historical financial values must not silently change because master data changed later.

Snapshot values may include:

- nightly rates
- subtotal
- extra-guest charges
- voucher discount
- final total
- applicable policy references
- payment amount

Changing a future pricing rule must not rewrite already-created financial history.

Adjustments or refunds must be represented as explicit financial actions/records.

---

## 12. Testing Is Mandatory

### Test directory

Project tests must be stored under the repository folder:

```text
test/
```

Do not silently create a parallel `tests/` directory unless a framework integration explicitly requires it and the project has been updated to support the chosen location consistently.

Suggested structure:

```text
test/
├── unit/
├── feature/
├── integration/
└── e2e/
```

Use the appropriate runner for the layer involved.

At minimum, backend/domain behavior should have automated coverage for critical booking rules.

### Minimum booking test matrix

When booking logic changes, verify at least:

- available cabin can be held
- overlapping reservation is rejected
- adjacent non-overlapping stays are allowed
- expired hold becomes available
- active hold blocks another customer
- concurrent booking allows only one valid hold
- duplicate booking request is idempotent
- payment success confirms only valid reservations
- late payment after hold expiry does not auto-confirm
- cancellation follows policy
- voucher quota is concurrency-safe
- manual admin booking cannot bypass availability
- blocked/maintenance periods prevent reservation
- QR check-in rejects invalid booking states

### Test-first behavior

For bugs and concurrency-sensitive changes:

1. Reproduce with a test when feasible.
2. Make the test fail for the right reason.
3. Implement the fix.
4. Re-run the focused test.
5. Run the wider relevant test suite.
6. Record the verification result in the task log.

Do not delete or weaken a test just to make the suite pass.

---

## 13. Mandatory Change Log After Code-Changing Work

**Every task that changes code or project configuration must produce a log file.**

Create:

```text
docs/log/
```

when it does not exist.

Create one Markdown log per logical code-changing task:

```text
docs/log/LOG-{ID}.md
```

Recommended ID format:

```text
YYYYMMDD-<short-slug>
```

Example:

```text
docs/log/LOG-20260926-booking-hold.md
```

The log must contain, at minimum:

```md
# LOG-{ID}

## Task
What was requested.

## Context / Assumptions
Important assumptions or constraints used.

## Changes
Files/components changed and what changed.

## Business Rules Affected
Relevant booking, pricing, payment, authorization, or data rules.

## Tests
Commands run, focused tests, and results.

## Verification
What was manually or automatically verified.

## Documentation
Which project docs were updated, if any.

## Memory Updates
Obsidian Vault: [updated / not available / not applicable]
Code-Base-Memory: [updated / not available / not applicable]

## Notes / Follow-ups
Remaining risks, known limitations, or next steps.
```

The log should be factual and concise. Do not invent successful verification.

---

## 14. Obsidian Vault Project Memory

Use the project's configured **Obsidian Vault** as persistent project memory when the relevant integration/tool is available.

The Vault should capture durable project knowledge such as:

- architecture decisions
- business-rule decisions
- important implementation decisions
- resolved ambiguities
- known constraints
- recurring debugging findings
- deployment/operational notes
- important integration behavior
- important decisions that future agents should not rediscover

Do not store secrets, passwords, tokens, API keys, or sensitive credentials in the Vault.

### Rule

If a task creates durable knowledge that is useful to future work:

1. Update the relevant Vault note.
2. Reference the update in `docs/log/LOG-{ID}.md`.

If the Vault integration is unavailable, do not pretend it was updated. Record:

```text
Obsidian Vault: unavailable
```

in the task log.

---

## 15. Code-Base-Memory MCP

Use the configured **Code-Base-Memory MCP** when available to preserve reusable repository knowledge.

Good candidates include:

- architecture conventions
- important directories and responsibilities
- domain invariants
- tricky concurrency behavior
- testing conventions
- recurring gotchas
- decisions that future coding agents need to remember
- relationships between modules that are not obvious from filenames alone

Do not store secrets or ephemeral noise.

After a meaningful code change:

1. Determine whether the change creates durable repository knowledge.
2. If yes, update Code-Base-Memory.
3. Record whether the update succeeded in the task log.

If the MCP is unavailable:

```text
Code-Base-Memory: unavailable
```

Do not claim the memory was updated.

---

## 16. Completion Gate

**Never declare a task "done", "complete", or "finished" immediately after editing code.**

Before declaring completion, verify:

```text
[ ] Requested implementation is present
[ ] Related tests were added/updated
[ ] Relevant tests pass
[ ] No obvious lint/type errors introduced
[ ] No unrelated files were changed unnecessarily
[ ] Required documentation is updated
[ ] docs/log/LOG-{ID}.md exists
[ ] Obsidian Vault was updated if applicable
[ ] Code-Base-Memory was updated if applicable
[ ] Final repository state is consistent
```

### Additional rule

For a task that changes booking/payment/concurrency behavior, the completion gate is not satisfied unless the relevant automated tests exist and pass.

---

## 17. "Before Project Completion" Final Test Requirement

Before declaring the **overall project** complete, not just an individual task:

1. Ensure the `test/` directory contains the project's final relevant automated tests.
2. Run the complete relevant test suite.
3. Run relevant static analysis/lint/type checks.
4. Verify the production build.
5. Inspect the final Git diff/status for unintended changes.
6. Create the final project log in `docs/log/`.
7. Update Obsidian Vault project memory with the final architecture/state where the integration is available.
8. Update Code-Base-Memory with durable repository knowledge where the integration is available.
9. Only then state that the project is complete.

A passing test command alone is not sufficient.

---

## 18. Secrets and Environment Variables

Never commit:

- API keys
- access tokens
- database passwords
- Midtrans production secrets
- Cloudflare R2 secrets
- private credentials
- webhook signing secrets

Use environment variables and appropriate secret management.

Do not write secrets into:

- `docs/log/`
- Obsidian Vault
- Code-Base-Memory
- comments
- tests
- README files

Use `.env.example` for variable names and safe placeholders only.

---

## 19. Safe Database Changes

For schema changes:

1. Update migration(s).
2. Update Eloquent models/relations.
3. Update validation and domain logic.
4. Update relevant tests.
5. Update ERD/docs if the model changed.
6. Verify migration direction and rollback behavior where applicable.

Never manually "fix" a development database in a way that bypasses migrations when the change belongs to the application schema.

---

## 20. API / UI Contract Rules

Even with Inertia, keep server/client responsibilities clear.

Server:

- authoritative data
- authorization
- validation
- calculations
- business rules
- transactions
- payment verification

Client:

- rendering
- interaction
- optimistic UX only when safe
- local UI state
- form state
- presentation

Never trust client-submitted:

- price
- discount
- payment state
- availability
- role/permission
- final totals

---

## 21. Development Workflow

For any code-changing task, use this workflow:

```text
1. Read instructions and relevant docs
   → verify: requirements/context understood

2. Inspect affected code
   → verify: existing implementation and conventions known

3. State assumptions/plan
   → verify: ambiguity is resolved or explicitly surfaced

4. Implement the smallest correct change
   → verify: code compiles/type-checks where applicable

5. Create/update tests
   → verify: intended behavior is executable

6. Run focused tests
   → verify: changed behavior passes

7. Run relevant broader checks
   → verify: no regression introduced

8. Update durable project memory
   → verify: Obsidian / Code-Base-Memory updated when available

9. Create docs/log/LOG-{ID}.md
   → verify: exact changes and test results recorded

10. Final status/diff inspection
   → verify: no accidental changes

11. Report completion with evidence
   → verify: never claim checks that were not actually run
```

---

## 22. Git Discipline

- Do not reset, revert, squash, or rewrite unrelated user changes.
- Do not force-push unless explicitly instructed.
- Keep commits focused if commits are part of the task.
- A log file is mandatory for code-changing work regardless of whether a Git commit is created.
- Do not fabricate commit hashes, test outputs, deployment results, or tool usage.

---

## 23. When an External Integration Is Missing

The project expects integration with:

- Obsidian Vault
- Code-Base-Memory MCP

If an integration is unavailable in the current environment:

- Continue with the code task when safe.
- Record the unavailable integration honestly in the log.
- Do not claim that external memory was updated.
- Do not block ordinary implementation solely because an optional memory integration is unavailable, unless the user explicitly requires it for that task.

---

## 24. Definition of Done

A normal code-changing task is done when:

```text
Implementation
+
Automated Tests
+
Verification
+
Change Log
+
Relevant Documentation
+
Relevant Memory Updates
=
Done
```

The **overall project** is done only after the full project-level verification in Section 17 has been completed.

---

## 25. These Guidelines Are Working If

- Fewer unnecessary changes appear in diffs.
- Fewer rewrites are needed because of missed requirements.
- Clarifying questions happen before implementation mistakes.
- Booking/concurrency behavior is covered by repeatable tests.
- Every code-changing task can be reconstructed from `docs/log/`.
- Future agents can recover important repository knowledge from the documentation, Obsidian Vault, and Code-Base-Memory without rediscovering it from scratch.
