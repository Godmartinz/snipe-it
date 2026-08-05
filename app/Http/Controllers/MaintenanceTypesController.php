<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MaintenanceTypesController extends Controller
{
    public function index(): View
    {
        $this->authorize('index', MaintenanceType::class);

        return view('maintenance-types.index');
    }

    public function create(): View
    {
        $this->authorize('create', MaintenanceType::class);

        return view('maintenance-types.edit')->with('item', new MaintenanceType);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', MaintenanceType::class);

        $type = new MaintenanceType;
        $type->name = $request->input('name');
        $type->created_by = auth()->id();

        if ($type->save()) {
            return redirect()->route('maintenance-types.index')
                ->with('success', trans('admin/maintenance_types/message.create.success'));
        }

        return redirect()->back()->withInput()->withErrors($type->getErrors());
    }

    public function edit(MaintenanceType $maintenanceType): View
    {
        $this->authorize('update', $maintenanceType);

        return view('maintenance-types.edit')->with('item', $maintenanceType);
    }

    public function update(Request $request, MaintenanceType $maintenanceType): RedirectResponse
    {
        $this->authorize('update', $maintenanceType);

        $maintenanceType->name = $request->input('name');

        if ($maintenanceType->save()) {
            return redirect()->route('maintenance-types.index')
                ->with('success', trans('admin/maintenance_types/message.update.success'));
        }

        return redirect()->back()->withInput()->withErrors($maintenanceType->getErrors());
    }

    public function destroy(MaintenanceType $maintenanceType): RedirectResponse
    {
        $this->authorize('delete', $maintenanceType);

        $maintenanceType->delete();

        return redirect()->route('maintenance-types.index')
            ->with('success', trans('admin/maintenance_types/message.delete.success'));
    }
}
