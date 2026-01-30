<label for="{{ $for }}" class = "font-medium">{{ $slot }}
    @if($required) 
        <span class = "text-red-600 tooltip tooltip-right" data-tip="Required">*</span>
    @else
        <span class = "text-gray-400 text-xs">(optional)</span>
    @endif
</label>