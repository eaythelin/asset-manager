@if(session('success'))
  <div class="fixed top-10 left-1/2 -translate-x-1/2 z-50 pointer-events-none"
    x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
    <div class="alert alert-success flex flex-row gap-5 pointer-events-none">
        <x-heroicon-o-check-circle class="size-5 sm:size-7" />
        <span class="sm:text-base font-medium">{{ session('success') }}</span>
    </div>
  </div>
@endif