<a href = "{{ route($routeName) }}" class="flex 
                                                items-center 
                                                hover:bg-yellow-700/20
                                                hover:text-yellow-400
                                                text-base 
                                                text-white 
                                                px-4 
                                                py-2 
                                                rounded 
                                                {{ request()->routeIs($routeName) ? 'font-bold text-yellow-400 bg-blue-800' : '' }}">
    {{ $slot }} {{ $title }}
</a>