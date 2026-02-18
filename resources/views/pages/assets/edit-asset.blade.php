@extends("layouts.pageslayout")
@section("content")

<div class="md:mx-4">
  <div class = "mb-4">
    <x-back-link route="assets.index">Return to Assets</x-back-link>
  </div>

  <x-validation-error />
  <form method="POST" action="{{ route("assets.update", $asset->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="bg-white p-4 rounded-2xl shadow-2xl mt-4">
      <div class="flex flex-row items-center gap-2 mb-4">
        <x-heroicon-s-information-circle class="size-6 text-blue-700"/>
        <p class="text-lg font-bold">General Details</p>
      </div>
      <div class="flex flex-col sm:flex-row gap-6">
        {{-- Left Column --}}
        <div class = "flex flex-col flex-1 gap-4">
          <div class="form-row">
            <x-page-label for="asset_code" :required="true">Asset Code</x-page-label>
            <x-page-input name="asset_code" id="asset_code" value="{{ $asset->asset_code }}" readonly/>
          </div>

          <div class="form-row">
            <x-page-label for="asset_name" :required="true">Asset Name</x-page-label>
            <x-page-input name="asset_name" id="asset_name" value="{{ old('asset_name', $asset->name) }}"/>
          </div>

          <div class="form-row">
            <x-page-label for="serial_name" :required="true">Serial Name</x-page-label>
            <x-page-input name="serial_name" id="serial_name" value="{{ old('serial_name', $asset->serial_name) }}"/>
          </div>

          <div class = "form-row">
            <x-page-label for="image_path">Asset Image</x-page-label>
            <div class="md:w-full md:px-9 md:ml-3">
              <input type="file" class="filepond-asset" name="image_path" id="image_path">
            </div>
          </div>
        </div>
        {{-- Right Column --}}
        <div class = "flex flex-col flex-1 gap-4">
          <div class="form-row">
            <x-page-label for="category" :required="true">Category</x-page-label>
            <x-page-select name="category" id="category">
              <option value="" disabled>--Select Category--</option>
              @foreach($categories as $id=>$name)
                <option value="{{ $id }}" {{ old('category', $asset->category_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
              @endforeach
            </x-page-select>
          </div>

          <div class = "form-row">
            <x-page-label for="subcategory">Subcategory</x-page-label>
            <x-page-select name="subcategory" id="subcategory" data-current-subcategory="{{ $asset->sub_category_id ?? '' }}" disabled>
              <option value="" disabled>--Select Subcategory--</option>
            </x-page-select>
          </div>

          <div class = "form-row">
            <x-page-label for="description">Description</x-page-label>
            <x-page-textarea name="description" id="description">
              {{ old('description', $asset->description) }} 
            </x-page-textarea>
          </div>
        </div>
      </div>

      <hr class="border-gray-300 m-5">
      <div class="flex flex-row items-center gap-2 mb-4">
        <x-heroicon-s-user-group class="size-6 text-green-700"/>
        <p class="text-lg font-bold">Assignment Details</p>
      </div>

      <div class="flex flex-col sm:flex-row gap-6">
        {{-- Left Column --}}
        <div class = "flex flex-col flex-1 gap-4">
          <div class="form-row">
            <x-page-label for="department" :required="true">Department</x-page-label>
            <x-page-select name="department" id="department">
              <option value="" disabled>--Select Department--</option>
              @foreach($departments as $id=>$name)
                <option value="{{ $id }}" {{ old('department', $asset->department_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
              @endforeach
            </x-page-select>
          </div>
        </div>

        {{-- Right Column --}}
        <div class = "flex flex-col flex-1 gap-4">
          <div class="form-row">
            <x-page-label for="custodian">Custodian</x-page-label>
            <x-page-select name="custodian" id="custodian">
              <option value="" disabled {{ old('custodian', $asset->custodian_id) ? '' : 'selected' }}>--Select Employee--</option>
              @foreach($employees as $id=>$name)
                <option value="{{ $id }}" {{ old('custodian', $asset->custodian_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
              @endforeach
            </x-page-select>
          </div>
        </div>
      </div>

      <hr class="border-gray-300 m-5">
      <div class="flex flex-row items-center gap-2 mb-4">
        <x-heroicon-s-currency-dollar class="size-6 text-yellow-400"/>
        <p class="text-lg font-bold">Financial Details</p>
      </div>

      <div class="flex flex-col sm:flex-row gap-6" x-data="{ isDepreciable: {{ old('is_depreciable', $asset->is_depreciable ?? false) ? 'true' : 'false' }} }">
        {{-- Left Column --}}
        <div class = "flex flex-col flex-1 gap-4">
          <div class="form-row">
            <x-page-label for="is_depreciable">
              Is Depreciable
              <span class="text-xs text-gray-500 align-super tooltip tooltip-info" data-tip="Asset will be included in asset depreciation">?</span>
            </x-page-label>
            <input x-model="isDepreciable" type="checkbox" class="checkbox border-2 border-gray-400" name="is_depreciable" id="is_depreciable">
          </div>

          <div class="form-row">
            <x-page-label for="cost" required="isDepreciable">Cost</x-page-label>
            <x-page-input name="cost" id="cost" value="{{ old('cost', $asset->cost) }}"/>
          </div>

          <div class="form-row">
            <x-page-label for="salvage_value" required="isDepreciable">Salvage Value</x-page-label>
            <x-page-input name="salvage_value" id="Salvage" value="{{ old('salvage_value', $asset->salvage_value) }}"/>
          </div>
        </div>

        {{-- Right Column --}}
        <div class = "flex flex-col flex-1 gap-4">
          <div class = "form-row">
            <x-page-label for="acquisition_date" required="isDepreciable">Acquisition Date</x-page-label>
            <input 
              class="input max-w-xs border-2 border-gray-400" 
              type="date" name="acquisition_date" 
              id="acquisition_date" 
              value="{{ old('acquisition_date', $asset->acquisition_date?->format('Y-m-d')) }}">
          </div>

          <div class = "form-row">
            <x-page-label for="useful_life_in_years" required="isDepreciable">Useful Life in Years</x-page-label>
            <x-page-input name="useful_life_in_years" id="useful_life_in_years" value="{{ old('useful_life_in_years', $asset->useful_life_in_years) }}"/>
          </div>

          <div class = "form-row">
            <x-page-label for="end_of_life_date" required="isDepreciable">End of Life Date</x-page-label>
            <input 
              class="input max-w-xs border-2 border-gray-400" 
              type="date" 
              name="end_of_life_date" 
              id="end_of_life_date" 
              value="{{ old('end_of_life_date', $asset->end_of_life_date?->format('Y-m-d')) }}"readonly>
          </div>
        </div>
      </div>
      <hr class="border-gray-300 m-5">
      <div class="flex flex-row items-center gap-2 mb-4">
        <x-heroicon-s-clipboard-document-list class="size-6 text-gray-600"/>
        <p class="text-lg font-bold">Misc. Details</p>
      </div>

      <div class="flex flex-col sm:flex-row gap-6">
        {{-- Left Column --}}
        <div class = "flex flex-col flex-1 gap-4">
          <div class="form-row">
            <x-page-label for="supplier">Supplier</x-page-label>
            <x-page-select name="supplier" id="supplier">
              <option value="" disabled>--Select Supplier--</option>
              @foreach($suppliers as $id=>$name)
                <option value="{{ $id }}" {{ old('supplier', $asset->supplier_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
              @endforeach
            </x-page-select>
          </div>
        </div>

        {{-- Right Column --}}
        <div class = "flex flex-col flex-1 gap-4"></div>
      </div>

      <div class="flex justify-end mt-2">
        <x-buttons type="submit" class="w-full md:w-auto">
          <x-heroicon-o-pencil-square class="size-4 sm:size-5" />
          Save Changes
        </x-buttons>
      </div>
    </div>
  </form>
</div>
@endsection

@section('scripts')
  @vite('resources/js/assets/edit-asset/getEditSubcategory.js')
  @vite('resources/js/assets/endOfLifeCalc.js')
  @vite('resources/js/assets/filepond-asset.js')
@endsection