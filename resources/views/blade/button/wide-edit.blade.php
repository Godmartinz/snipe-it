@props([
    'item' => null,
    'route' => null,
])

@can('update', $item)
<!-- start update button component -->
@if ($item->deleted_at=='')
<a href="{{ ($item->deleted_at == '') ? $route: '#' }}" class="btn btn-block btn-sm btn-warning btn-social hidden-print{{ ($item->deleted_at!='') ? ' disabled' : '' }}">
    <x-icon type="edit" />
    {{ trans('general.update') }}
</a>
@endif
<!-- end update button component -->
@endcan