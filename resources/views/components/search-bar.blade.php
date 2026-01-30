<form method = "GET" action="{{ route( $route ) }}" {{ $attributes->merge(["class" => ""]) }}>
    <div class = "flex flex-row gap-3">
        <input 
        type="text" 
        placeholder="{{ $placeholder }}" 
        class="input input-bordered w-full"
        name="search"
        value="{{ request('search') }}"
        />
        <x-buttons type="submit">Search</x-buttons>
    </div>
</form>