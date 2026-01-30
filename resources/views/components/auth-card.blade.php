<div class = "min-h-screen flex items-center justify-center px-4 bg-gradient-to-br from-blue-900 via-blue-700 to-yellow-600">
  <div class = "card p-6 w-full max-w-sm shadow-2xl bg-base-100 border-t-5 border-b-5 border-yellow-400 rounded-lg">
    <div class = "card-body w-full">
      <div class = "text-center p-2">
        <h1 class = "font-bold text-xl text-blue-800">{{ $title }}</h1>
      </div>
      {{ $slot }}
    </div>
  </div>
</div>