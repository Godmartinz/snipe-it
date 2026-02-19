@props([
    'count' => null,
    'model' => null,
])

@aware(['class'])

@can('view', $model)
    <x-tabs.nav-item
            name="history"
            icon_type="history"
            label="{{ trans('general.history') }}"
            count="{{ $count }}"
            tooltip="{{ trans('general.history') }}"
    />
@endcan