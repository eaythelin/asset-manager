<div class="navbar bg-base-100 border-b border-gray-300 shadow-sm sticky top-0 z-10 lg:px-6">
  <div class="flex-none xl:hidden">
    <!-- Drawer toggle button on mobile -->
    <label for="my-drawer" class="btn btn-square btn-ghost">
      <x-heroicon-o-bars-3 class="h-6 w-6" />
    </label>
  </div>
  <div class="flex-1">
    <a class="btn btn-ghost text-xl md:text-2xl font-bold" href = "{{ route('dashboard.index') }}">Asset Manager</a>
  </div>
  <div class="flex items-center gap-3">
    <div class="avatar placeholder tooltip tooltip-left" data-tip = "{{ Auth::user() -> name}} | {{ Auth::user() -> getRoleNames() -> first() }}">
      <div class="bg-blue-800 text-neutral-content w-10 rounded-full flex items-center justify-center mr-2 sm:mr-0">
        <span class="text-sm md:text-base text-white">{{ auth()->user()->getInitials() }}</span>
      </div>
    </div>
    <div class = "hidden sm:block">
      <!--Get the name of user-->
      <div class="font-medium text-xs md:text-sm">{{ auth()->user() -> name}}</div>
      <!--Get the role of user-->
      <div class="text-xs md:text-sm opacity-60">{{ auth()->user() -> getRoleNames() -> first() }}</div>
    </div>
  </div>
</div>