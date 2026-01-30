<select {{ $attributes->merge(["class" => "select border-2 border-gray-400 rounded-lg"]) }}>
    {{ $slot }}
</select>