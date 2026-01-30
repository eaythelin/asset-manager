@extends("layouts.pageslayout")
@section("content")
<x-pages-header title="Subcategories" description="View and manage asset subcategories">
  <x-heroicon-s-folder-open class="text-blue-800 size-8 md:size-10"/>
</x-pages-header>
<x-toast-success />
<x-session-error />

<div class = "md:m-4">
  <x-validation-error />
  
  <div class = "bg-white p-4 rounded-2xl shadow-xl">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4 mx-2">

      <x-search-bar route="subcategory.index" placeholder="Search subcategories..."/>
      
      @can('manage sub-categories')
        <x-buttons class="w-full sm:w-auto" onclick="createSubCategory.showModal()">
          <x-heroicon-s-plus class="size-5"/>
          Create Subcategory
        </x-buttons>
      @endcan
    </div>
    <x-tables :columnNames="$columns" :centeredColumns="[4]">
      <tbody class = "divide-y divide-gray-400">
          @foreach($subCategories as $subCategory)
            <tr>
              <th class = "p-3 text-center">{{ $subCategory->id }}</th>
              <x-td>{{ $subCategory->name}}</x-td>
              <x-td>{{ $subCategory->category->name}}</x-td>
              <x-td>{{ $subCategory->description}}</x-td>
              <td class = "flex flex-col sm:flex-row gap-2 sm:gap-4 justify-center">
                @can("manage sub-categories")
                  <x-buttons onclick="editSubCategory.showModal()"
                    class="editBtn tooltip tooltip-top"
                    data-tip="Edit"
                    aria-label="Edit Subcategory"
                    data-subcategory="{{ json_encode([
                      'name'=>$subCategory->name,
                      'category_id'=>$subCategory->category->id,
                      'description'=>$subCategory->description,
                      'route'=>route('subcategory.update', $subCategory->id)]) }}">
                    <x-heroicon-o-pencil-square class="size-4 sm:size-5" />
                  </x-buttons>
                  <x-buttons onclick="deleteSubCategory.showModal()"
                    class="deleteBtn bg-red-700 tooltip tooltip-top"
                    data-tip="Delete"
                    aria-label="Delete Subcategory"
                    data-route="{{ route('subcategory.delete', $subCategory->id) }}">
                    <x-heroicon-s-trash class="size-4 sm:size-5"/>
                  </x-buttons>
                @endcan
              </td>
            </tr>
          @endforeach
        </tbody>
    </x-tables>
    <div class = "text-base-content">
      {{ $subCategories->links() }}
    </div>
  </div>
</div>

@include('modals.subcategory-modals.create-subcategory-modal')
@include('modals.subcategory-modals.edit-subcategory-modal')
@include('modals.subcategory-modals.delete-subcategory-modal')
@endsection

@section('scripts')
  @vite('resources/js/subcategory/delete-subcategory.js')
  @vite('resources/js/subcategory/edit-subcategory.js')
@endsection