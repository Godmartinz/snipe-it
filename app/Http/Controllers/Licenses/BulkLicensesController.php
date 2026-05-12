<?php

namespace App\Http\Controllers\Licenses;

use App\Http\Controllers\Controller;
use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class BulkLicensesController extends Controller
{
    public function destroy(Request $request)
    {
        $this->authorize('delete', License::class);

        $errors = [];
        $success_count = 0;

        foreach ($request->ids as $id) {
            $license = License::find($id);

            if (is_null($license)) {
                $errors[] = trans('admin/licenses/message.does_not_exist');

                continue;
            }

            if (! Gate::allows('delete', $license)) {
                $errors[] = trans('general.insufficient_permissions');

                continue;
            }

            if ($license->assigned_seats_count > 0) {
                $errors[] = trans('admin/licenses/message.delete.bulk_checkout_warning', ['license_name' => $license->name]);

                continue;
            }

            // Since assigned_seats_count == 0, all seats already have assigned_to and asset_id as null,
            // so this update is effectively a no-op. It mirrors the single destroy() and is kept as a
            // safety net. Bypassing Eloquent events here is intentional and safe — there is nothing
            // assigned to trigger events on. Prior checkout/checkin history is preserved in action_log
            // (keyed by LicenseSeat item_type/item_id) and remains accessible even after soft-delete.
            DB::table('license_seats')
                ->where('license_id', $license->id)
                ->update(['assigned_to' => null, 'asset_id' => null]);

            $license->licenseseats()->delete();
            $license->delete();
            $success_count++;
        }

        if (count($errors) > 0) {
            if ($success_count > 0) {
                return redirect()->route('licenses.index')
                    ->with('success', trans_choice('admin/licenses/message.delete.partial_success', $success_count, ['count' => $success_count]))
                    ->with('multi_error_messages', $errors);
            }

            return redirect()->route('licenses.index')->with('multi_error_messages', $errors);
        }

        return redirect()->route('licenses.index')->with('success', trans('admin/licenses/message.delete.bulk_success'));
    }
}
