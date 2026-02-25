@props([
    'item' => null,
    'route' => null,
    'count' => null,
    'type' => 'item',
])

@can('delete', $item)
    <!-- start delete button component -->
    @if ((method_exists($item, 'isDeletable')) && ($item->deleted_at==''))
        @if (!$item->isDeletable())
            <button class="btn btn-block btn-sm btn-danger btn-social hidden-print disabled" data-tooltip="true"  data-placement="top" data-title="{{ trans('general.cannot_be_deleted') }}">
                <x-icon type="delete" />
                {{ trans('general.delete') }}
            </button>
        @else
            <button class="btn btn-block btn-sm btn-danger btn-social delete-asset" data-toggle="modal" title="{{ trans('general.delete_what', ['item'=> trans('general.'.$type)]) }}" data-content="{{ trans('general.sure_to_delete_var', ['item' => $item->name]) }}" data-target="#dataConfirmModal" data-tooltip="true" data-icon="fa fa-trash" data-placement="top" data-title="{{ trans('general.delete_what', ['item'=> trans('general.'.$type)]) }}" onClick="return false;">
                <x-icon type="delete" />
                {{ trans('general.delete') }}
            </button>
        @endif
    @endif
    <!-- end delete button component -->
@endif

