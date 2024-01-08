<div>
    @include('partials.forms.edit.accessory-select',['fieldname' => $accessory_request, 'translated_name' => 'Accessory Request', 'multiple' => 'multiple'])

    <!-- Notes -->
        <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
            <label for="notes" class="col-md-3 control-label">{{ trans('admin/hardware/form.notes') }}</label>
            <div class="col-md-7 col-sm-12">
                <textarea class="col-md-6 form-control" id="notes" aria-label="notes" name="notes" style="min-width:100%;">

                </textarea>
                {!! $errors->first('notes', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
            </div>
        </div>

    {{--@include ('partials.forms.edit.notes', ['item' => $request])--}}
</div>

