<?php

namespace App\Services;

use App\Events\CheckoutableCheckedOut;
use App\Models\Asset;
use App\Models\PredefinedKit;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class incapsulates checkout logic for reuse in different controllers
 *
 * @author [D. Minaev.] [<dmitriy.minaev.v@gmail.com>]
 */
class PredefinedKitCheckoutService
{
    use AuthorizesRequests;

    /**
     * @param  Request  $request,  this function works with fields: checkout_at, expected_checkin, note
     * @param  PredefinedKit  $kit  kit for checkout
     * @param  User  $user  checkout target
     * @return array Empty array if all ok, else [string_error1, string_error2...]
     */
    public function checkout(Request $request, PredefinedKit $kit, User $user)
    {
        try {

            // Check if the user exists
            if (is_null($user)) {
                return ['errors' => trans('admin/users/message.user_not_found')];
            }

            $errors = [];

            $assets_to_add = $this->getAssetsToAdd($kit, $user, $errors);
            $license_seats_to_add = $this->getLicenseSeatsToAdd($kit, $user, $errors);
            $consumables_to_add = $this->getConsumablesToAdd($kit, $user, $errors);
            $accessories_to_add = $this->getAccessoriesToAdd($kit, $user, $errors);

            if (count($errors) > 0) {
                return ['errors' => $errors];
            }

            $checkout_at = date('Y-m-d H:i:s');
            if (($request->filled('checkout_at')) && ($request->input('checkout_at') != date('Y-m-d'))) {
                $checkout_at = $request->input('checkout_at');
            }

            $expected_checkin = '';
            if ($request->filled('expected_checkin')) {
                $expected_checkin = $request->input('expected_checkin');
            }

            $admin = auth()->user();

            $note = e($request->input('note'));

            $errors = $this->saveToDb($user, $admin, $checkout_at, $expected_checkin, $errors, $assets_to_add, $license_seats_to_add, $consumables_to_add, $accessories_to_add, $note);

            return ['errors' => $errors, 'assets' => $assets_to_add, 'accessories' => $accessories_to_add, 'consumables' => $consumables_to_add];
        } catch (ModelNotFoundException $e) {
            return ['errors' => [$e->getMessage()]];
        } catch (CheckoutNotAllowed $e) {
            return ['errors' => [$e->getMessage()]];
        }
    }

    protected function getAssetsToAdd($kit, $user, &$errors)
    {
        $models = $kit->models()
            ->with(['assets' => function ($hasMany) {
                $hasMany->RTD();
            }])
            ->get();
        $assets_to_add = [];
        foreach ($models as $model) {
            $assets = $model->assets;
            $quantity = $model->pivot->quantity;
            $firstBlockedByCompany = null;
            foreach ($assets as $asset) {
                if (
                    $asset->availableForCheckout()
                    && ! $asset->is($user)
                ) {
                    // FMCS tenant isolation. Skip candidate assets whose
                    // company doesn't allow this checkout target. Every
                    // other checkout sink (single, bulk, API, accessory,
                    // license, consumable) enforces this via canCheckoutTo.
                    // The kit path historically didn't, which let a
                    // multi-company non-superuser assign a company-A asset
                    // to a company-B-only user by routing through kits.
                    if (! $asset->canCheckoutTo($user)) {
                        $firstBlockedByCompany ??= $asset;

                        continue;
                    }
                    $this->authorize('checkout', $asset);
                    $quantity -= 1;
                    $assets_to_add[] = $asset;
                    if ($quantity <= 0) {
                        break;
                    }
                }
            }
            if ($quantity > 0) {
                // Prefer the cross-company message when the shortfall was
                // caused by canCheckoutTo filtering rather than genuine
                // unavailability, so the operator understands why a kit
                // that visibly contains the model still can't be handed
                // to this target. Reference the specific blocked asset's
                // company since asset models themselves are not per-company.
                if ($firstBlockedByCompany !== null) {
                    $errors[] = trans('general.error_checkout_company_mismatch', [
                        'item' => trans('general.asset').' "'.$firstBlockedByCompany->display_name.'"',
                        'item_company' => $firstBlockedByCompany->company?->name ?? trans('general.unassigned'),
                        'target' => trans('general.user').' "'.($user->name ?? $user->username ?? $user->id).'"',
                    ]);
                } else {
                    $errors[] = trans('admin/kits/general.none_models', ['model' => $model->name, 'qty' => $model->pivot->quantity]);
                }
            }
        }

        return $assets_to_add;
    }

    protected function getLicenseSeatsToAdd($kit, $user, &$errors)
    {
        $seats_to_add = [];
        $licenses = $kit->licenses()
            ->with('freeSeats')
            ->get();
        foreach ($licenses as $license) {
            // FMCS tenant isolation, see comment in getAssetsToAdd.
            if (! $license->canCheckoutTo($user)) {
                $errors[] = trans('general.error_checkout_company_mismatch', [
                    'item' => trans('general.license').' "'.$license->name.'"',
                    'item_company' => $license->company?->name ?? trans('general.unassigned'),
                    'target' => trans('general.user').' "'.($user->name ?? $user->username ?? $user->id).'"',
                ]);

                continue;
            }
            $this->authorize('checkout', $license);
            $quantity = $license->pivot->quantity;
            if ($quantity > count($license->freeSeats)) {
                $errors[] = trans('admin/kits/general.none_licenses', ['license' => $license->name, 'qty' => $license->pivot->quantity]);
            }
            for ($i = 0; $i < $quantity; $i++) {
                $seats_to_add[] = $license->freeSeats[$i];
            }
        }

        return $seats_to_add;
    }

    protected function getConsumablesToAdd($kit, $user, &$errors)
    {
        $consumables = $kit->consumables()->with('users')->get();
        $eligible = [];
        foreach ($consumables as $consumable) {
            // FMCS tenant isolation, see comment in getAssetsToAdd.
            if (! $consumable->canCheckoutTo($user)) {
                $errors[] = trans('general.error_checkout_company_mismatch', [
                    'item' => trans('general.consumable').' "'.$consumable->name.'"',
                    'item_company' => $consumable->company?->name ?? trans('general.unassigned'),
                    'target' => trans('general.user').' "'.($user->name ?? $user->username ?? $user->id).'"',
                ]);

                continue;
            }
            $this->authorize('checkout', $consumable);
            if ($consumable->numRemaining() < $consumable->pivot->quantity) {
                $errors[] = trans('admin/kits/general.none_consumables', ['consumable' => $consumable->name, 'qty' => $consumable->pivot->quantity]);
            }
            $eligible[] = $consumable;
        }

        return $eligible;
    }

    protected function getAccessoriesToAdd($kit, $user, &$errors)
    {
        $accessories = $kit->accessories()->with('users')->get();
        $eligible = [];
        foreach ($accessories as $accessory) {
            // FMCS tenant isolation, see comment in getAssetsToAdd.
            if (! $accessory->canCheckoutTo($user)) {
                $errors[] = trans('general.error_checkout_company_mismatch', [
                    'item' => trans('general.accessory').' "'.$accessory->name.'"',
                    'item_company' => $accessory->company?->name ?? trans('general.unassigned'),
                    'target' => trans('general.user').' "'.($user->name ?? $user->username ?? $user->id).'"',
                ]);

                continue;
            }
            $this->authorize('checkout', $accessory);
            if ($accessory->numRemaining() < $accessory->pivot->quantity) {
                $errors[] = trans('admin/kits/general.none_accessory', ['accessory' => $accessory->name, 'qty' => $accessory->pivot->quantity]);
            }
            $eligible[] = $accessory;
        }

        return $eligible;
    }

    protected function saveToDb($user, $admin, $checkout_at, $expected_checkin, $errors, $assets_to_add, $license_seats_to_add, $consumables_to_add, $accessories_to_add, $note)
    {
        $errors = DB::transaction(
            function () use ($user, $admin, $checkout_at, $expected_checkin, $errors, $assets_to_add, $license_seats_to_add, $consumables_to_add, $accessories_to_add, $note) {
                // assets
                foreach ($assets_to_add as $asset) {
                    $asset->location_id = $user->location_id;

                    // Concurrency guard, same shape as Api\AssetsController::checkout.
                    // Kit checkout can race with any other checkout of the same asset.
                    // Re-fetch under lockForUpdate and re-check availability before
                    // invoking checkOut; a claimed asset gets skipped rather than
                    // producing a duplicate history row and counter bump.
                    $locked = Asset::whereKey($asset->id)->lockForUpdate()->first();
                    if (! $locked || ! $locked->availableForCheckout()) {
                        $errors[] = trans('admin/hardware/message.checkout.not_available').' ('.$asset->asset_tag.')';

                        continue;
                    }

                    $error = $asset->checkOut($user, $admin, $checkout_at, $expected_checkin, $note, null);
                    if ($error) {
                        array_merge_recursive($errors, $asset->getErrors()->toArray());
                    }
                }
                // licenses
                foreach ($license_seats_to_add as $licenseSeat) {
                    $licenseSeat->created_by = $admin->id;
                    $licenseSeat->assigned_to = $user->id;
                    if ($licenseSeat->save()) {
                        event(new CheckoutableCheckedOut($licenseSeat, $user, $admin, $note));
                    } else {
                        $errors[] = 'Something went wrong saving a license seat';
                    }
                }
                // consumables
                foreach ($consumables_to_add as $consumable) {
                    $consumable->assigned_to = $user->id;
                    $consumable->users()->attach($consumable->id, [
                        'consumable_id' => $consumable->id,
                        'user_id' => $admin->id,
                        'assigned_to' => $user->id,
                    ]);
                    event(new CheckoutableCheckedOut($consumable, $user, $admin, $note));
                }
                // accessories
                foreach ($accessories_to_add as $accessory) {
                    $accessory->assigned_to = $user->id;
                    $accessory->users()->attach($accessory->id, [
                        'accessory_id' => $accessory->id,
                        'user_id' => $admin->id,
                        'assigned_to' => $user->id,
                    ]);
                    event(new CheckoutableCheckedOut($accessory, $user, $admin, $note));
                }

                return $errors;
            }
        );

        return $errors;
    }
}
