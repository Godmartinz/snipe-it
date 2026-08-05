@can('delete', \App\Models\Category::class)
    <x-table.bulk-actions
        name="category"
        :action_route="route('categories.bulk.delete')"
        model_name="category"
        :actions="[
            'delete' => ['label' => trans('general.delete')],
        ]"
    />
@endcan
