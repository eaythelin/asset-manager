@if($breakline)
    <hr class="border-gray-300 m-5">
@endif
<div class = "flex flex-row items-center gap-2 mb-4">
    {{ $slot }}
    <p class="text-lg font-bold">{{ $title }}</p>
</div>