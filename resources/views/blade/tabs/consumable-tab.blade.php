@props([
    'count' => null,
])
@aware(['class'])

@can('view', \App\Models\Consumable::class)
    <x-tabs.nav-item
            name="consumables"
            icon_type="consumable"
            label="{{ trans('general.consumables') }}"
            count="{{ $count }}"
            tooltip="{{ trans('general.consumables') }}"
    />
@endcan