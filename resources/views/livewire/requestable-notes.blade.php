<div>
    @include('partials.forms.edit.accessory-select',['fieldname' => 'addAccessoryRequest', 'translated_name' => 'Accessory Request', 'select_id' => 'accessory_request[]',  'multiple' => 'multiple'])

    @include('partials.forms.edit.license-select',['fieldname' =>'addLicenseRequest', 'translated_name' => 'License Request', 'select_id' => 'license_request','multiple' => 'multiple'])
    <!-- Notes -->
        <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
            <label for="notes" class="col-md-3 control-label">{{ trans('admin/hardware/form.notes') }}</label>
            <div class="col-md-7 col-sm-12">
                @if(isset($accessory_request))
                    {{trans('admin/accessories/general.accessory_name')}}
                @endif
                    @foreach($accessory_request as $accessory)
                        {{$accessory}}<br>
                    @endforeach
                    @foreach($license_request as $license)
                        {{$license->name}}<br>
                    @endforeach
{{--                {!! $errors->first('notes', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}--}}
            </div>
        </div>

    {{--@include ('partials.forms.edit.notes', ['item' => $request])--}}
</div>


@section('moar_scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('addAccessoryRequest', function (value) {
                @this.call('addAccessoryRequest', value);
            });

            Livewire.on('addLicenseRequest', function (value) {
                @this.call('addLicenseRequest', value);
            });
        });
    </script>
@endsection
