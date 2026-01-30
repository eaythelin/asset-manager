<div class="{{ $bgColor }} text-white p-4 rounded-xl">
<div class = "flex flex-row items-center justify-between">
    <div class = "flex flex-col">
        <h1 class = "font-medium text-sm md:text-base">{{ $title }}</h1>
        <span class = "text-2xl md:text-3xl">{{ $number }}</span>
    </div>
    {{ $slot }}
</div>
</div>