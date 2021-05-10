<div class="form-group">
    <label for="{{ $name }}" class="control-label">{{ $label }}:</label>
    <textarea class="form-control"
                @if (isset($edit))
                id="{{ $name }}-edit"
                @else
                id="{{ $name }}"
                @endif
                name="{{ $name }}"
                @isset ($attrs)
                @foreach ($attrs as $attr)
                    {{ $attr['name'] }} = "{{ $attr['value'] }}"
                @endforeach
                @endif
                cols="30"
                rows="3">
    @if (isset($value))
        {{ $value }}
    @endif
    </textarea>
</div>
