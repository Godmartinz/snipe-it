@props([
    'count' => null,
    'class' => false,
    'item' => false,
])

@can('view', $item)
<x-tabs.nav-item
        :$class
        name="files"
        icon_type="files"
        label="{{ trans('general.files') }}"
        count="{{ $count }}"
        tooltip="{{ trans('general.files') }}"
/>
@endcan