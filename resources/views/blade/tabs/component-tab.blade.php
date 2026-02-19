@props([
    'count' => null,
])
@aware(['class'])

@can('view', \App\Models\Component::class)
    <x-tabs.nav-item
            name="components"
            icon_type="component"
            label="{{ trans('general.components') }}"
            count="{{ $count }}"
            tooltip="{{ trans('general.components') }}"
    />
@endcan