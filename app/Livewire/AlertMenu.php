<?php

namespace App\Livewire;

use App\Helpers\Helper;
use Illuminate\View\View;
use Livewire\Attributes\Lazy;
use Livewire\Component;

/**
 * Top-nav alert bell. Wraps the old blade/alert-menu.blade.php partial so
 * the low-inventory + deprecation queries no longer block first-paint.
 *
 * `#[Lazy]` makes the component render a lightweight placeholder on the
 * initial page load, then Livewire fires a second XHR to hydrate the
 * real body — so `Helper::checkLowInventory()` and
 * `Helper::deprecationCheck()` (both cached at the helper layer with
 * observer-driven invalidation) never sit on the critical render path.
 *
 * The setting-gate lives on the tag in layouts/default.blade.php:
 * `@if ($snipeSettings->show_alerts_in_menu == '1') <livewire:alert-menu />`
 * so nothing about this component runs when the operator turned the
 * bell off.
 */
#[Lazy]
class AlertMenu extends Component
{
    public function placeholder(): string
    {
        // Reserve the same visual footprint as the loaded bell so the
        // top-nav doesn't shift when the real component swaps in. The
        // hidden label reserves badge width so hydration with alerts
        // does not push the surrounding nav items sideways. Kept in
        // sync with the same-shape hidden-label in the loaded view
        // (livewire/alert-menu.blade.php) so the "no alerts" state also
        // preserves the reserved width.
        // Match the loaded view's markup exactly — same icon, same
        // sr-only span position, same label markup with no surrounding
        // whitespace. Any inline-element width difference between the
        // placeholder and the loaded state translates directly into a
        // top-nav layout shift on hydration.
        $srOnly = trans('general.alerts');

        return <<<HTML
            <li class="dropdown tasks-menu" aria-busy="true">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="far fa-flag" aria-hidden="true"></i>
                    <span class="sr-only">{$srOnly}</span>
                    <span class="label label-danger" aria-hidden="true" style="visibility: hidden">0</span>
                </a>
            </li>
        HTML;
    }

    public function render(): View
    {
        $alert_items = Helper::checkLowInventory();
        $deprecations = Helper::deprecationCheck();

        return view('livewire.alert-menu', [
            'alert_items' => $alert_items,
            'deprecations' => $deprecations,
            'alert_count' => count($alert_items) + count($deprecations),
        ]);
    }
}
