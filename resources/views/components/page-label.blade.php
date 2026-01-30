<label for="{{ $for }}" class="font-medium w-40">
    {{ $slot }}
    @if($required) 
        <span x-show = "{{ $required }}" class = "text-red-600 tooltip tooltip-right" data-tip="Required">*</span>
    @endif
</label>