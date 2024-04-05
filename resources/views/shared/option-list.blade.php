@isset($addEmptyOption)
    @if($addEmptyOption == true)
        <option></option>
    @endif
@endif
@isset($options)
    @foreach($options as $option)
        <option value="{{ $option->id }}"
        @isset($selectedValue) {{ $option->id == $selectedValue ? 'selected' : '' }} @endisset
        >{{ $option->name }}
        </option>
    @endforeach
@endisset
