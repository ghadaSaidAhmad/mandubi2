@if (!isset($group))
    <div class="form-group">
        <label for="{{ $name }}" class="control-label">{{ $input_label }}:</label>
        <select name="{{ $name }}" id="{{ $name }}" class="select2 form-control" style="max-width: 100%!important;">
            @foreach($options as $option)
                @if (isset($object) && $option->$value == $object)
                    <option value="{{ $option->$value }}" selected>
                        @if (is_array($label))
                            @foreach($label as $item){{ $option->$item }} @endforeach
                        @else
                            {{ $option->$label }}
                        @endif

                    </option>
                @else
                    <option value="{{ $option->$value }}">
                        @if (is_array($label))
                            @foreach($label as $item){{ $option->$item }} @endforeach
                        @else
                            {{ $option->$label }}
                        @endif
                    </option>
                @endif
            @endforeach
        </select>
    </div>
@elseif($group)
    <div class="form-group">
        <label for="{{ $name }}" class="control-label">{{ $input_label }}:</label>
        <select name="{{ $name }}" id="{{ $name }}" class="select2 form-control" style="max-width: 100%!important;">
            @foreach($options as $option)
                <optgroup label="{{ $option->name }}">
                    @foreach($option->rowChilds as $sub)
                        @if (isset($object) && $sub->$value == $object)
                            <option value="{{ $sub->$value }}" selected>
                                @if (is_array($label))
                                    @foreach($label as $item){{ $option->$item }} @endforeach
                                @else
                                    {{ $sub->$label }}
                                @endif
                            </option>
                        @else
                            <option value="{{ $sub->$value }}">
                                @if (is_array($label))
                                    @foreach($label as $item){{ $option->$item }} @endforeach
                                @else
                                    {{ $sub->$label }}
                                @endif
                            </option>
                        @endif
                    @endforeach
                </optgroup>
            @endforeach
        </select>
    </div>
@endif
