@props([
    'route' => null,
    'name' => 'default',
])
<!-- start licenses tab pane -->
@can('view', \App\Models\License::class)

        <x-slot:header>
            {{ trans('general.licenses') }}
        </x-slot:header>

        <x-slot:content>
            <x-table
                    show_column_search="true"
                    show_advanced_search="true"
                    buttons="licenseButtons"
                    api_url="{{ $route }}"
                    :presenter="\App\Presenters\LicensePresenter::dataTableLayout()"
                    export_filename="export-{{ str_slug($name) }}-licenses-{{ date('Y-m-d') }}"
            />
        </x-slot:content>

@endcan
<!-- end licenses tab pane -->