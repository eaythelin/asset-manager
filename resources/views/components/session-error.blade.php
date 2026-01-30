<!--This is for session errors!!-->
@if(session('error'))
  <div class="toast toast-center fixed top-10 right-0 z-50"
    x-data="{ show: true}" x-show="show" x-init="setTimeout(() => show = false, 3000)">
    <div class="alert alert-warning flex flex-row gap-5">
      <x-heroicon-o-exclamation-triangle class="size-5 sm:size-7"/>
      <span class = "sm:text-base font-medium">{{ session('error') }}</span>
    </div>
  </div>
@endif