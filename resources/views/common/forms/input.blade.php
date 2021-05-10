<div class="form-group">
    <label for="{{ $name }}" class="control-label">{{ $label }}:</label>
    <input
            @if (!isset($type))
            @php
                $type = 'text'
            @endphp
            @endif
            type="{{ $type }}"
            class="form-control"
            @if (isset($edit))
            id="{{ $name }}-edit"
            @else
            id="{{ $name }}"
            @endif
            name="{{ $name }}"
            @if (isset($value))
            value="{{ $value }}"
            @endif
            @isset ($attrs)
                @foreach ($attrs as $attr)
                    {{ $attr['name'] }} = "{{ $attr['value'] }}"
                @endforeach
            @endif
    >
</div>
