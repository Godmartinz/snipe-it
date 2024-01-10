<div>
    @include('partials.forms.edit.accessory-select',['fieldname' => 'accessory_request', 'translated_name' => 'Accessory Request', 'select_id' => 'accessory_request',  'multiple' => 'multiple'])

    @include('partials.forms.edit.license-select',['fieldname' =>'license_request', 'translated_name' => 'License Request', 'select_id' => 'license_request','multiple' => 'multiple'])
    <!-- Notes -->
        <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
            <label for="notes" class="col-md-3 control-label">{{ trans('admin/hardware/form.notes') }}</label>
            <div class="col-md-7 col-sm-12">
                <textarea class="col-md-6 form-control" id="notes" aria-label="notes" name="notes" style="min-width:100%;">
                {{$accessory_request, $license_request}}
                </textarea>
                {!! $errors->first('notes', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
            </div>
        </div>

    {{--@include ('partials.forms.edit.notes', ['item' => $request])--}}
</div>

@section('moar_scripts')
    <script>
        $(document).ready(function () {
            $('#accessory_request').on('change', function () {
                @this.set('accessory_request', $(this).val());
            });

            $('#license_request').on('change', function () {
                @this.set('license_request', $(this).val());
            });
        });
    </script>
@endsection
