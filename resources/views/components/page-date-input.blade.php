<input {{ $attributes->merge(["class" => "input max-w-xs border-2 border-gray-400"]) }}
    type="date" name="{{ $name }}"
    id="{{ $id }}"
    value="{{ $value }}">