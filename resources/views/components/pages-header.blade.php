<div class="mb-6">
  <div class = "flex items-center gap-5">
    {{ $slot }}
    <div>
      <div class="font-bold text-xl md:text-2xl">{{ $title }}</div>
      <div class="text-xs md:text-base opacity-60">{{ $description }}</div>
    </div>
  </div>
  <hr class="border-gray-300 mt-2">
</div>