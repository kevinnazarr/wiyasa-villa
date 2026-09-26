# `.github`

Konfigurasi GitHub repository untuk Wiyasa Villa.

## Contents

- `workflows/ci.yml` — CI untuk Laravel/Pest, PostgreSQL, Redis, frontend build, dan project policy.
- `dependabot.yml` — update dependency npm, Composer, dan GitHub Actions.
- `PULL_REQUEST_TEMPLATE.md` — checklist quality gate PR.
- `ISSUE_TEMPLATE/bug_report.yml` — bug report.
- `ISSUE_TEMPLATE/feature_request.yml` — feature request.
- `CODEOWNERS` — ownership repository; ganti placeholder sebelum digunakan.

## CI Principles

CI harus memverifikasi minimal:

1. Composer dependency integrity.
2. PostgreSQL connectivity dan migrations.
3. Redis availability.
4. Laravel/Pest tests.
5. Frontend build.
6. Required project files.
7. Monorepo boundary: tidak ada `apps/api` pada current architecture.

CI tidak menggantikan `AGENTS.md`. Agent workflow tetap harus membuat test, log, documentation, dan memory updates sesuai project rules.
