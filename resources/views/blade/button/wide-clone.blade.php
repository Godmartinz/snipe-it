@props([
    'item' => null,
    'route' => null,
])

@can('create', $item)
    <!-- start clone button component -->
    <a href="{{ $route }}" class="btn btn-block btn-sm btn-info btn-social hidden-print">
    <x-icon type="clone" />
    {{ trans('general.clone') }}
    <!-- end clone button component -->
</a>
@endcan