@extends("layouts.pageslayout")
@section("content")
<x-pages-header title="Categories" description="View and manage asset categories">
  <x-heroicon-c-square-2-stack class="text-blue-800 size-8 md:size-10"/>
</x-pages-header>

<x-toast-success />
<x-session-error />

<div class = "md:m-4">
  <x-validation-error />
  
  <div class = "bg-white p-4 rounded-2xl shadow-xl">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4 mx-2">

      <x-search-bar route="category.index" placeholder="Search categories..."/>
      
      @can('manage categories')
        <x-buttons class="w-full sm:w-auto" onclick="createCategory.showModal()">
          <x-heroicon-s-plus class="size-5"/>
          Create Category
        </x-buttons>
      @endcan
    </div>
    <x-tables :columnNames="$columns" :centeredColumns="[3]">
      <tbody class = "divide-y divide-gray-300">
          @foreach($categories as $category)
            <tr>
              <th class = "p-3 text-center">{{ $category -> id }}</th>
              <x-td>{{ $category->name }}</x-td>
              <x-td>{{ $category->description}}</x-td>
              <td class = "flex flex-row gap-2 sm:gap-4 justify-center">
                @can("manage categories")
                  <x-buttons onclick="editCategory.showModal()"
                    class="editBtn tooltip tooltip-top"
                    data-tip="Edit"
                    aria-label="Edit Category"
                    data-category="{{ json_encode([
                    'name'=>$category->name,
                    'description'=>$category->description,
                    'route'=>route('category.update', $category->id), JSON_HEX_QUOT | JSON_HEX_APOS]) }}">
                    <x-heroicon-o-pencil-square class="size-4 sm:size-5" />
                  </x-buttons>
                  <x-buttons onclick="deleteCategory.showModal()"
                    class="deleteBtn bg-red-700 tooltip tooltip-top"
                    data-tip="Delete"
                    aria-label="Delete Category"
                    data-route="{{ route('category.delete', $category->id) }}">
                    <x-heroicon-s-trash class="size-4 sm:size-5"/>
                  </x-buttons>
                @endcan
              </td>
            </tr>
          @endforeach
        </tbody>
    </x-tables>
    <div class = "text-base-content">
      {{ $categories->links() }}
    </div>
  </div>
</div>

@include('modals.category-modals.create-category-modal')
@include('modals.category-modals.edit-category-modal')
@include('modals.category-modals.delete-category-modal')
@endsection

@section('scripts')
  @vite('resources/js/category/delete-category.js')
  @vite('resources/js/category/edit-category.js')
@endsection