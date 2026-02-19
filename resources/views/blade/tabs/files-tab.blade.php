@props([
    'count' => null,
])

@aware(['class'])

<x-tabs.nav-item
        name="files"
        icon_type="files"
        label="{{ trans('general.files') }}"
        count="{{ $count }}"
/>