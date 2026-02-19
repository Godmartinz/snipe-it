@props([
    'count' => null,
])
@aware(['class'])

@can('view', \App\Models\Asset::class)
    <x-tabs.nav-item
            :$class
            name="assets"
            icon_type="assets"
            label="{{ trans('general.assets') }}"
            count="{{ $count }}"
            tooltip="{{ trans('general.assets') }}"
    />
@endcan