<div>

    <!-- License -->
        <div   class="form-group">
            {{ Form::label('license_request', trans('license_request')) }}
            <div class="col-md-7">
                <select
                        wire:model.lazy="license_request"
                        aria-label="license_request"
                        id="license_request"
                        class="form-control"
                >
                    <option value="">{{ trans('general.select_license') }}</option>
                    @foreach ($licenses as $id => $license)
                        <option value="{{ $id }}">
                            {{ $license->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            {!! $errors->first('license_request', '<div class="col-md-8 col-md-offset-3"><span class="alert-msg"><i class="fas fa-times"></i> :message</span></div>') !!}
        </div>
{{--        <!-- Accessory -->--}}
{{--        <div id="assigned_accessory" class="form-group{{ $errors->has($fieldname) ? ' has-error' : '' }}"{!!  (isset($style)) ? ' style="'.e($style).'"' : ''  !!}>--}}
{{--            {{ Form::label($fieldname, $translated_name, array('class' => 'col-md-3 control-label')) }}--}}
{{--            <div class="col-md-7{{  ((isset($required) && ($required =='true'))) ?  ' required' : '' }}">--}}
{{--                <select class="js-data-ajax select2  livewire-select2" wire:model='{{$fieldname}}' data-endpoint="accessories" data-placeholder="{{ trans('general.select_accessory') }}" name="{{ $fieldname }}" style="width: 100%" id="{{ (isset($select_id)) ? $select_id : 'assigned_accessory_select' }}"{{ (isset($multiple)) ? ' multiple' : '' }}>--}}

{{--                    @if ((!isset($unselect)) && ($accessory_id = Request::old($fieldname, (isset($accessory) ? $accessory->id  : (isset($item) ? $item->{$fieldname} : '')))))--}}
{{--                        <option value="{{ $accessory_id }}" selected="selected">--}}
{{--                            {{ (\App\Models\Accessory::find($accessory_id)) ? \App\Models\Accessory::find($accessory_id)->present()->name : '' }}--}}
{{--                        </option>--}}
{{--                    @else--}}
{{--                        @if(!isset($multiple))--}}
{{--                            <option value="">{{ trans('general.select_accessory') }}</option>--}}
{{--                        @endif--}}
{{--                    @endif--}}
{{--                </select>--}}
{{--            </div>--}}
{{--            {!! $errors->first($fieldname, '<div class="col-md-8 col-md-offset-3"><span class="alert-msg"><i class="fas fa-times"></i> :message</span></div>') !!}--}}

{{--        </div>--}}

        <!-- Notes -->
        <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
            <label for="notes" class="col-md-3 control-label">{{ trans('admin/hardware/form.notes') }}</label>
            <div class="col-md-7 col-sm-12">
                <textarea class="col-md-6 form-control" id="notes" aria-label="notes" name="notes" style="min-width:100%;"></textarea>
                {!! $errors->first('notes', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
            </div>
        </div>

    {{--@include ('partials.forms.edit.notes', ['item' => $request])--}}
</div>
{{--<script>--}}
{{--    $(document).ready(function() {--}}
{{--        $('#requested_licenses').select2();--}}

{{--        $('#requested_licenses').on('change', function() {--}}
{{--            let data = $(this).val();--}}
{{--            $wire.set('licenses', data);--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}
