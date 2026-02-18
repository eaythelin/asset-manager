<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
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

  <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
  <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
  {{-- Page-specific scripts --}}
  @yield("scripts")
</body>
</html>
