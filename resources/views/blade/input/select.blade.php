{{-- options: either an array of key => value pairs, or omit and pass
     the option markup via the default slot. --}}
@props([
    'options',
    'selected' => null,
    'includeEmpty' => false,
    'forLivewire' => false,
    'required' => false,
])

<select
    {{ $attributes->class(['select2', 'livewire-select2' => $forLivewire]) }}
    @required($required)
    @if($forLivewire) data-livewire-component="{{ $this->getId() }}" @endif
>
    @if($includeEmpty)
        <option value=""></option>
    @endif
    {{-- map the simple key => value pairs when nothing is passed in via the slot --}}
        @if($slot->isEmpty())
            @foreach($options as $key => $value)
                @if(is_array($value))
                    <optgroup label="{{ $key }}">
                        @foreach($value as $optionKey => $optionValue)
                            <option
                                    value="{{ $optionKey }}"
                                    @selected(is_array($selected) ? in_array($optionKey, $selected) : $selected == $optionKey)
                            >
                                {{ $optionValue }}
                            </option>
                        @endforeach
                    </optgroup>
                @else
                    <option
                            value="{{ $key }}"
                            @selected(is_array($selected) ? in_array($key, $selected) : $selected == $key)
                    >
                        {{ $value }}
                    </option>
                @endif
            @endforeach
        @else
            {{ $slot }}
        @endif
</select>
