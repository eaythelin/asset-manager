<dialog id="{{ $name }}" class="modal">
  <div class="modal-box shadow-2xl">
    <form method="dialog">
      <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </form>
    <div class = "flex flex-col gap-2 text-center p-4 mb-2">
      <h2 class="text-xl font-bold">{{ $title }}</h2>
      <hr class="border-gray-400 mt-2">
    </div>
    {{ $slot }}
  </div>
</dialog>