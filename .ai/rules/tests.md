---
paths:
  - 'tests/**'
---

# Tests

## Database refresh comes from the base TestCase
`Tests\TestCase` already applies `LazilyRefreshDatabase` and seeds settings via `InitializesSettings`. Do not add `RefreshDatabase`, `DatabaseTransactions`, or `DatabaseMigrations` to an individual test.

## Test methods are snake_case
Name test methods in snake_case: `test_page_renders()`, `test_requires_permission()`. Never camelCase.

## Authenticate API tests with actingAsForApi()
Use `$this->actingAsForApi($user)` in API tests and `$this->actingAs($user)` in UI tests.
