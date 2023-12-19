

<div>
@section('inputFields')
    <div wire:ignore>
        @include ('partials.forms.edit.license-select', ['translated_name' => trans('general.licenses_available'),'fieldname' => 'requested_licenses', 'select_id' => 'requested_licenses'] )
{{--        @include ('partials.forms.edit.accessory-select', ['translated_name' => trans('general.accessories'), 'fieldname' => 'requested_accessories','select_id' => 'requested_accessories'] )--}}
    </div>
        <!-- Notes -->
        <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
            <label for="notes" class="col-md-3 control-label">{{ trans('admin/hardware/form.notes') }}</label>
            <div class="col-md-7 col-sm-12">
                <textarea class="col-md-6 form-control" id="notes" aria-label="notes" name="notes" style="min-width:100%;"></textarea>
                {!! $errors->first('notes', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
            </div>
        </div>


    {{--@include ('partials.forms.edit.notes', ['item' => $request])--}}
@stop
</div>
<script>
    $(document).ready(function() {
        $('#requested_licenses').select2();

        $('#requested_licenses').on('change', function() {
            let data = $(this).val();
            $wire.set('licenses', data);
        });
    });
</script>
