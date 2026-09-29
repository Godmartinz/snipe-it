@props([
    'route',
    'table_header' => trans('general.orders'),
    'quantity_formatter' => null,
])

<x-slot:table_header>
    {{ $table_header }}
</x-slot:table_header>

<x-table
        :presenter="\App\Presenters\OrderItemsPresenter::dataTableLayout($quantity_formatter)"
    show_advanced_search="false"
    api_url="{{ $route }}"
    fixed_number="false"
    fixed_right_number="false"
    export_filename="export-orders-{{ date('Y-m-d') }}"
/>
