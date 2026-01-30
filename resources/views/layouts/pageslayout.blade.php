<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <script src="//unpkg.com/alpinejs" defer></script>
  <title>Fixed Asset Management System</title>
</head>
<body>
  <div class="drawer lg:drawer-open">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col lg:ml-80 min-h-screen">
      @include("partials.pages-navbar")

      <div class="flex-1 p-7 bg-base-300">
        @yield("content")
      </div>
    </div>

    @include("partials.pages-sidebar")    
  </div>

  {{-- Page-specific scripts --}}
  @yield("scripts")
</body>
</html>
