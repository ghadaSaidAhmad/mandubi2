<input type="{{ $type }}"
        class="form-control"
        @if (isset($edit))
        id="{{ $name }}-edit"
        @else
        id="{{ $name }}"
        @endif
        name="{{ $name }}"
        @if (isset($value))
        value="$value"
        @endif
        @isset ($attrs)
            @foreach ($attrs as $attr)
                {{ $attr['name'] }} = "{{ $attr['value'] }}"
            @endforeach
        @endif
>
