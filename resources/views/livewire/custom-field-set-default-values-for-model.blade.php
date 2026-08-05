<span>
    <div class="form-group{{ $errors->has('custom_fieldset') ? ' has-error' : '' }}">
        <label for="custom_fieldset" class="col-md-3 control-label">
            {{ trans('admin/models/general.fieldset') }}
        </label>
        <div class="col-md-5">
             <x-input.select
                 name="fieldset_id"
                 id="fieldset_id"
                 :options="Helper::customFieldsetList()"
                 :selected="old('fieldset_id', $fieldset_id)"
                 :for-livewire="true"
                 class="js-fieldset-field"
                 style="width:100%; min-width:350px;"
                 aria-label="custom_fieldset"
             />
            <x-form.error name="custom_fieldset" />
        </div>
        <div class="col-md-3">
            @if ($fieldset_id)
                <label class="form-control">
                    <input
                        type="checkbox"
                        name="add_default_values"
                        value="1"
                        id="add_default_values"
                        wire:model.live="add_default_values"
                        data-livewire-component="{{ $this->getId() }}"
                        @disabled($this->fields->isEmpty())
                    />
                    {{ trans('admin/models/general.add_default_values') }}
                </label>
            @endif
        </div>
    </div>

    @if ($add_default_values)

        @if ($this->fields)

                @foreach ($this->fields as $field)
                    <div class="form-group" wire:key="field-{{ $field->id }}">

                        <label class="col-md-3 control-label{{ $errors->has($field->db_column_name()) ? ' has-error' : '' }}">{{ $field->name }}</label>

                        <div class="col-md-7">

                                @if ($field->format == "DATE")

                                    <div class="input-group col-md-4" style="padding-left: 0px;">
                                        {{-- wire:model + the input-event dispatch on change is the
                                             usual Livewire workaround so the picker's value change
                                             flows back to the component. See
                                             https://laracasts.com/discuss/channels/livewire/livewire-and-bootstrap-datepicker?page=1&replyId=623122 --}}
                                        <x-input.datepicker
                                            id="default-value{{ $field->id }}"
                                            name="default_values[{{ $field->id }}]"
                                            wire:model="selectedValues.{{ $field->db_column }}"
                                            onchange="this.dispatchEvent(new InputEvent('input'))"
                                        />
                                    </div>

                                @elseif ($field->element == "text")


                                    <input
                                        class="form-control"
                                        type="text"
                                        id="default-value{{ $field->id }}"
                                        name="default_values[{{ $field->id }}]"
                                        wire:model="selectedValues.{{ $field->db_column }}"
                                    />


                                @elseif($field->element == "textarea")


                                        <textarea
                                            class="form-control"
                                            style="width: 100%;"
                                            id="default-value{{ $field->id }}"
                                            name="default_values[{{ $field->id }}]"
                                            wire:model="selectedValues.{{ $field->db_column }}"
                                        ></textarea>


                                @elseif($field->element == "listbox")


                                        <select class="form-control" name="default_values[{{ $field->id }}]" wire:model="selectedValues.{{ $field->db_column }}">
                                            <option value=""></option>
                                            @foreach(explode("\r\n", $field->field_values) as $field_value)
                                                <option
                                                    value="{{$field_value}}"
                                                    wire:key="listbox-{{ $field_value }}"
                                                >
                                                    {{ $field_value }}
                                                </option>
                                            @endforeach
                                        </select>


                                @elseif($field->element == "radio")

                                    @foreach(explode("\r\n", $field->field_values) as $field_value)
                                        <label class="col-md-3 form-control" for="{{ $field->db_column }}_{{ str_slug($field_value) }}" wire:key="radio-{{ $field_value }}">
                                            <input
                                                id="{{ $field->db_column }}_{{ str_slug($field_value) }}"
                                                aria-label="{{ str_slug($field->name) }}"
                                                type="radio"
                                                name="default_values[{{ $field->id }}]"
                                                value="{{$field_value}}"
                                                wire:model="selectedValues.{{ $field->db_column }}"
                                            />{{ $field_value }}
                                        </label>
                                    @endforeach

                                @elseif($field->element == "checkbox")

                                     @foreach(explode("\r\n", $field->field_values) as $field_value)
                                        <label class="col-md-3 form-control" for="{{ $field->db_column }}_{{ str_slug($field_value) }}" wire:key="checkbox-{{ $field_value }}">
                                            <input
                                                id="{{ $field->db_column }}_{{ str_slug($field_value) }}"
                                                type="checkbox"
                                                aria-label="{{ str_slug($field->name) }}"
                                                name="default_values[{{ $field->id }}][]"
                                                value="{{ $field_value }}"
                                                wire:model="selectedValues.{{ $field->db_column }}"
                                            > {{ $field_value }}
                                        </label>
                                    @endforeach


                                @else
                                    <span class="help-block form-error">
                                        Unknown field element: {{ $field->element }}
                                    </span>
                                @endif
                                        <?php
                                        $errormessage = $errors->first($field->db_column_name());
                                        if ($errormessage) {
                                            print('<span class="alert-msg" role="alert" aria-live="assertive">'.$errormessage.'</span>');
                                        }
                                        ?>
                        </div>
                    </div>

            @endforeach

            @endif

    @endif
</span>
