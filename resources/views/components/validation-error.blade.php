@if($errors->any())
    <div class="alert alert-error mb-4 p-0 overflow-hidden relative" id="error-alert"
        x-data="{ show: true}" x-show="show" x-init="setTimeout(()=>show = false, 300000)">
        <!-- Close button -->
        <button 
            type="button" 
            onclick="document.getElementById('error-alert').remove()" 
            class="absolute top-2 right-2 z-10 btn btn-ghost btn-xs btn-circle text-error-content hover:bg-error-content/20"
        >
            <x-heroicon-o-x-circle class="size-5 sm:size-8"/>
        </button>

        <!-- Collapse wrapper -->
        <div class="collapse collapse-arrow w-full">
            <input type="checkbox" class="peer" />
            
            <!-- Shows the total amount of errors -->
            <div class="collapse-title font-semibold bg-error text-error-content flex flex-row gap-3 pr-10">
                <x-heroicon-c-exclamation-circle class="size-5"/>
                {{ $errors->count() }} errors occurred. Click to see details.
            </div>

            <!-- all the errors listed here -->
            <div class="collapse-content">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif