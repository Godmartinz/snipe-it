# Snipe-IT Testing

- Feature tests live in `tests/Feature/`, organized by entity (e.g. `tests/Feature/Assets/AssetsTest.php`). Unit tests live in `tests/Unit/`.
- Feature tests hit the database. The test environment uses `array` drivers for cache, session, and mail.
- Always build test data with model factories. Check for an existing custom state before setting attributes by hand.
- Test methods are named in snake_case: `test_page_renders()`, `test_requires_permission()`. Never camelCase.
- UI GET routes should have both a `test_page_renders` test and a `test_requires_permission` test.

```bash
php artisan test tests/Feature/Assets/AssetsTest.php   # single file
php artisan test --filter test_some_method             # single method
```
