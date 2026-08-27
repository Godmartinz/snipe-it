<?php

namespace App\Livewire;

use App\Models\Accessory;
use App\Models\Asset;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Sidebar rendered next to the single-item checkout forms (assets, licenses,
 * accessories, consumables). Shows a table of items already checked out to
 * whichever target the operator has picked — user, asset, or location —
 * so they can see what the target already has before completing the checkout.
 *
 * The parent page hosts three non-Livewire select2 widgets (user / asset /
 * location) plus a radio-group toggle from checkout-selector.blade.php that
 * controls which of those is visible. A small bridge script inside this
 * component's blade listens to all four inputs and dispatches
 * `checkout-target-selected` with the current target-type + id. This
 * component picks the event up, re-resolves the relevant relation, and
 * re-renders.
 */
class CheckoutTargetPanel extends Component
{
    private const TYPES = ['assets', 'licenses', 'accessories', 'consumables', 'components'];

    private const TARGET_TYPES = ['user', 'asset', 'location'];

    /**
     * The item type this sidebar displays — the type being checked OUT.
     * Locked so the client can't tamper: the parent checkout page pins it
     * at mount based on which flow the user is in.
     */
    #[Locked]
    public string $type;

    /**
     * Fallback target type for pages that don't render the checkout-selector
     * radio group (components go only to assets, consumables only to users).
     * The bridge script uses this as the assumed target when it can't find
     * an `input[name="checkout_to_type"]:checked`.
     */
    #[Locked]
    public string $defaultTargetType = 'user';

    public ?string $targetType = null;

    public ?int $targetId = null;

    public function mount(string $type, string $defaultTargetType = 'user'): void
    {
        if (! in_array($type, self::TYPES, true)) {
            throw new \InvalidArgumentException("Unknown checkout-target-panel type: {$type}");
        }

        if (! in_array($defaultTargetType, self::TARGET_TYPES, true)) {
            throw new \InvalidArgumentException("Unknown checkout-target-panel defaultTargetType: {$defaultTargetType}");
        }

        $this->type = $type;
        $this->defaultTargetType = $defaultTargetType;
    }

    /**
     * Bridge landing spot: any change to the target selects or the radio
     * toggle in the parent form dispatches to us with the current shape.
     * Nullable values come through when the operator clears a select.
     */
    #[On('checkout-target-selected')]
    public function targetSelected(?string $targetType, ?string $targetId): void
    {
        $this->targetType = in_array($targetType, self::TARGET_TYPES, true) ? $targetType : null;
        $this->targetId = $targetId !== null && $targetId !== '' ? (int) $targetId : null;
    }

    public function render(): View
    {
        return view('livewire.checkout-target-panel', [
            'items' => $this->items(),
            'noun' => $this->itemNoun(),
            'targetNoun' => $this->targetNoun(),
        ]);
    }

    private function items(): Collection
    {
        if ($this->targetType === null || $this->targetId === null) {
            return collect();
        }

        $target = $this->resolveTarget();
        if (! $target || ! Gate::allows('view', $target)) {
            return collect();
        }

        // Not every (item, target) combo is a valid checkout path in the
        // schema — consumables only go to users, licenses only to users
        // or assets. Falling out to an empty collection is intentional:
        // the operator switched to a target type that this item can't
        // actually be checked out to, so there's nothing to show.
        //
        // Location targets use the polymorphic `assignedAssets` relation
        // (assets checked OUT to this location via assigned_to/
        // assigned_type), NOT Location::assets() which is location_id-based
        // and means "assets physically at this location". Same story for
        // accessories: query the checkout pivot rather than the
        // location_id column.
        return match ("{$this->type}:{$this->targetType}") {
            'assets:user' => $target->assets,
            'licenses:user' => $target->licenses,
            'accessories:user' => $target->accessories,
            'consumables:user' => $target->consumables,

            'assets:asset' => $target->assignedAssets,
            'licenses:asset' => $target->licenses,
            'accessories:asset' => $target->accessories,

            'assets:location' => $target->assignedAssets,
            'accessories:location' => Accessory::whereHas('checkouts', function ($q) {
                $q->where('assigned_type', Location::class)
                    ->where('assigned_to', $this->targetId);
            })->get(),

            'components:asset' => $target->components,

            default => collect(),
        };
    }

    private function resolveTarget(): ?Model
    {
        return match ($this->targetType) {
            'user' => User::find($this->targetId),
            'asset' => Asset::find($this->targetId),
            'location' => Location::find($this->targetId),
            default => null,
        };
    }

    private function itemNoun(): string
    {
        return match ($this->type) {
            'assets' => trans('general.assets'),
            'licenses' => trans('general.licenses'),
            'accessories' => trans('general.accessories'),
            'consumables' => trans('general.consumables'),
            'components' => trans('general.components'),
        };
    }

    private function targetNoun(): string
    {
        return match ($this->targetType) {
            'user' => trans('general.user'),
            'asset' => trans('general.asset'),
            'location' => trans('general.location'),
            default => trans('general.user'),
        };
    }
}
