{{-- See snipeit_modals.js for what powers this --}}
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">{{ trans('admin/kits/general.append_license') }}</h4>
        </div>
        <div class="modal-body">
            <form class="form-horizontal" action="{{ route('api.kits.licenses.store', $kitId) }}" onsubmit="return false">
                {{ csrf_field() }}
                <x-alert type="danger" id="modal_error_msg" style="display:none">
                </x-alert>

                <div class="dynamic-form-row">
                    <label for="modal-license_id" class="col-md-4 col-xs-12 control-label">{{ trans('general.license') }}:</label>
                    <div class="col-md-8 col-xs-12 required">
                        <select class="js-data-ajax" data-endpoint="licenses" name="license" style="width: 100%" id="modal-license_id"></select>
                    </div>
                </div>

                <div class="dynamic-form-row">
                    <label for="modal-quantity_id" class="col-md-4 col-xs-12 control-label">{{ trans('general.quantity') }}:</label>
                    <div class="col-md-8 col-xs-12 required">
                        <input type='text' name='quantity' id='modal-quantity_id' class="form-control" value="1">
                    </div>
                </div>

            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('button.cancel') }}</button>
            <button type="button" class="btn btn-primary" id="modal-save">{{ trans('general.save') }}</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
