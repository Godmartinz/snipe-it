@props([
    'presenter' => null,
    'buttons' => null,
    'export_filename' => 'export-'.date('Y-m-d'),
    'api_url' => null,
    'show_column_search' => null,
    'show_advanced_search' => null,
    'show_search' => true,
    'fixed_number' => null,
    'fixed_right_number' => null,
    'sort_order' => 'asc',
    'sort_field' => 'name',
])

@aware(['name'])

{{-- fixed_number / fixed_right_number pin the first / last N columns
     via CSS position:sticky (snipe-table--sticky-*-N in overrides.less).
     The bootstrap-table fixed-columns feature that used to handle this
     was pulled from the bundle — its clone-and-overlay approach
     misplaced tooltips when the container reflowed and drifted in
     height with long-content rows. --}}
<table
    role="table"
    @class([
        'table', 'table-striped', 'snipe-table',
        'snipe-table--sticky-right-' . $fixed_right_number => (bool) $fixed_right_number,
        'snipe-table--sticky-left-' . $fixed_number => (bool) $fixed_number,
    ])
    data-cookie-id-table="{{ $name }}ListingTable"
    data-id-table="{{ $name }}ListingTable"
    data-sort-order="{{ $sort_order }}"
    data-toolbar="#{{ Illuminate\Support\Str::camel($name) }}Toolbar"
    data-bulk-button-id="#{{ Illuminate\Support\Str::camel($name) }}Button"
    data-bulk-form-id="#{{ Illuminate\Support\Str::camel($name) }}Form"
    data-selected-count-id="#{{ Illuminate\Support\Str::camel($name) }}SelectedCount"
    id="{{ $name }}ListingTable"
    data-show-columns-search="{{ $show_column_search }}"
    data-show-advanced-search="{{ $show_advanced_search }}"
    {{-- Deeplinking piggybacks on advanced search: if a page opts a table into
         the modal-driven advanced search, it also gets shareable ?filter[...] URLs. --}}
    data-advanced-search-deeplink="{{ filter_var($show_advanced_search, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false' }}"
    data-search="{{ $show_search }}"
    data-footer-style="footerStyle"
    data-show-footer="true"

    @if ($presenter)
        data-columns="{{ $presenter }}"
    @endif

    @if ($buttons)
        data-buttons="{{ $buttons }}"
    @endif

    @if ($api_url)
        data-side-pagination="server"
        data-url="{!!  $api_url !!}"
    @endif

    data-export-options='{
        "fileName": "{{ $export_filename }}",
        "ignoreColumn": ["actions","available_actions", "image","change","checkbox","checkincheckout","icon"]
    }'>
</table>

