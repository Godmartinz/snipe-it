@props([
    'count' => null,
])

@can('view', \App\Models\Asset::class)
    <x-tabs.nav-item
            name="rtd_assets"
            icon="fa-solid fa-house-flag fa-fw"
            label="{{ trans('admin/hardware/form.default_location') }}"
            count="{{ $count }}"
            tooltip="{{ trans('admin/hardware/form.default_location') }}"
    />
@endcan