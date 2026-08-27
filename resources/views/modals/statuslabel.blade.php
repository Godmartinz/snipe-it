{{-- See snipeit_modals.js for what powers this --}}
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title">{{ trans('admin/statuslabels/table.create') }}</h2>
        </div>
        <div class="modal-body">
            <form class="form-horizontal" action="{{ route('api.statuslabels.store') }}" onsubmit="return false">
                <x-alert type="danger" id="modal_error_msg" style="display:none">
                </x-alert>
                <div class="dynamic-form-row">
                    <label for="modal-name" class="col-md-3 col-xs-12 control-label">{{ trans('admin/statuslabels/table.name') }}:</label>
                    <div class="col-md-9 col-xs-12"><input type="text" name="name" id="modal-name" class="form-control" maxlength="191" required></div>
                </div>

                <div class="dynamic-form-row">
                    <label for="modal-type" class="col-md-3 col-xs-12 control-label">{{ trans('admin/statuslabels/table.status_type') }}:</label>
                    <div class="col-md-9 col-xs-12">
                        <x-input.select
                            name="type"
                            id="modal-type"
                            :options="$statuslabel_types"
                            required
                            style="width:100%;"
                        />
                    </div>
                </div>
            </form>
        </div>
        <div class="dynamic-form-row">
            @include('modals.partials.footer')
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
