# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Stack

- **PHP 8.2+** / **Laravel 12** (framework), **Laravel Mix** (webpack) for frontend assets
- **AdminLTE 2** / **Bootstrap 3** UI — Blade views, no Livewire/Inertia
- **Chart.js v2.9.4** — bundled at `public/js/dist/Chart.min.js`; use `horizontalBar` type (v2 API, not v3)

## Common Commands

```bash
# Run all tests
php artisan test
# or
vendor/bin/phpunit

# Run a single test file
php artisan test tests/Feature/Assets/AssetsTest.php

# Run a specific test method
php artisan test --filter testSomeMethod

# Build frontend assets (dev)
npm run dev

# Build for production
npm run prod

# Laravel Mix watch
npm run watch

# Tinker / REPL
php artisan tinker

# Clear caches after config/route changes
php artisan optimize:clear
```

Dev server is served via **Laravel Herd** (`herd coverage` for coverage reports).

## Architecture

### Controllers

Two parallel controller trees:
- `app/Http/Controllers/` — web/UI controllers (Blade views)
- `app/Http/Controllers/Api/` — REST API controllers (JSON, used by datatables + select2)

Subdirectory groupings: `Assets/`, `Licenses/`, `Users/`, `Accessories/`, `Consumables/`, `Components/`, `Kits/`, `Account/`, `Auth/`

### API Pattern

Every API controller returns data via a **Transformer** (`app/Http/Transformers/`). Never return raw model attributes from API controllers — always pass through the transformer. `DatatablesTransformer` wraps paginated results.

```php
return (new AssetsTransformer)->transformAssets($assets, $assets->count());
```

### Authorization

All authorization goes through **Policies** (`app/Policies/`). `CheckoutablePermissionsPolicy` is the base for assets/licenses/accessories/consumables — its `checkout()` / `checkin()` methods accept `$item = null` so you can use `@can('checkout', \App\Models\Asset::class)` without an instance.

### FMCS (Full Multiple Company Support)

`Setting::getSettings()->full_multiple_companies_support == '1'` gates company-scoped filtering. The select2 API endpoints (`selectlist()` methods) accept a `companyId` query param — apply it like this:

```php
if ((Setting::getSettings()->full_multiple_companies_support == '1') && ($request->filled('companyId'))) {
    $query->where('table.company_id', $request->input('companyId'));
}
```

Pass `data-company-id="{{ $user->company_id }}"` in Blade to wire it to select2.

### Select2 AJAX Dropdowns

Use `class="js-data-ajax"` with `data-endpoint="hardware|licenses|consumables|..."`. `snipeit.js` auto-initializes these, forwarding `data-company-id` as `companyId` and `data-asset-status-type` as `statusType` to the API.

### Routes

All routes are in `routes/web.php` (UI) and `routes/api.php` (API). Breadcrumbs are defined inline using `->breadcrumbs(fn (Trail $trail) => ...)` from `tabuna/breadcrumbs`. Every UI route should have a breadcrumb.

Note: the `reports/unaccepted_assets` route is named with slashes, not dots — use `route('reports/unaccepted_assets')`.

### Translations

String keys live in `resources/lang/en-US/general.php` (and other files in that directory). Always add new UI strings as translation keys rather than hard-coding English.

### Checkout Redirect Flow

After checkout, `Helper::getRedirectOption()` reads `$request->redirect_option`. For redirecting back to the assigned user after checkout:
- Set `redirect_option=target` in the form
- Set `checkout_to_type=user` in the form
- Set `assigned_user={{ $user->id }}` in the form

### Key Helper Methods (`app/Helpers/Helper.php`)

- `Helper::deployableStatusLabelList()` — status labels for checkout forms
- `Helper::defaultChartColors()` — 10-color palette used in charts
- `Helper::getRedirectOption($request, $id, $table)` — post-checkout redirect logic

### Global View Variables

`$snipeSettings` is injected into all views via a service provider — no need to pass `Setting::getSettings()` from every controller. Use it directly in Blade.

## Testing

Tests live in `tests/Feature/` (organized by entity) and `tests/Unit/`. Feature tests hit the database; the test environment uses `array` cache/session/mail drivers. Tests use factories for data setup.
