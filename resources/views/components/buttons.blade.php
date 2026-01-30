<!--$attribute->merge allows the component to be able to receive classes-->
<button {{ $attributes->merge(["class" => "btn 
                                                            flex flex-row gap-2 
                                                            bg-blue-800 text-white rounded-lg font-bold hover:bg-yellow-600/30 hover:text-yellow-800 active:bg-yellow-600 active:text-white"]) }}>
    {{ $slot }}
</button>