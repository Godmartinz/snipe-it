{{-- See snipeit_modals.js for what powers this --}}
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h2 class="modal-title">{{ trans('admin/maintenance_types/general.create') }}</h2>
        </div>
        <div class="modal-body">
            <form action="{{ route('api.maintenance-types.store') }}" onsubmit="return false">
                <div class="dynamic-form-row">
                    @include('partials.forms.edit.name', ['item' => new \App\Models\MaintenanceType(), 'translated_name' => trans('general.name')])
                </div>
            </form>
        </div>
        <div class="dynamic-form-row">
            @include('modals.partials.footer')
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
