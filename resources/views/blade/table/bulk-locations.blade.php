@can('delete', \App\Models\Location::class)
    <x-table.bulk-actions
        name="locations"
        :action_route="route('locations.bulkdelete.show')"
        model_name="location"
        :actions="[
            'delete' => ['label' => trans('general.delete')],
        ]"
    />
@endcan
