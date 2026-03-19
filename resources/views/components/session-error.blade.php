@if(session('error'))
  <div class="toast toast-top toast-center z-50 pointer-events-none"
    x-data="{ show: true}" x-show="show" x-init="setTimeout(() => show = false, 3000)">
    <div class="alert alert-warning flex flex-row gap-3 mt-10">
      <x-heroicon-o-exclamation-triangle class="size-5 sm:size-7 shrink-0"/>
      <span class="sm:text-base font-medium whitespace-nowrap">{{ session('error') }}</span>
    </div>
  </div>
@endif