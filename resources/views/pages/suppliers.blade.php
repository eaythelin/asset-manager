@extends("layouts.pageslayout")
@section("content")
<x-pages-header title="Suppliers" description="View and manage asset suppliers">
  <x-heroicon-s-truck class="text-blue-800 size-8 md:size-10"/>
</x-pages-header>

<x-toast-success />
<x-session-error />

<div class = "md:m-4">
  <x-validation-error />
  
  <div class = "bg-white p-4 rounded-2xl shadow-xl">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4 mx-2">

      <x-search-bar route="suppliers.index" placeholder="Search suppliers..."/>
      
      @can('manage suppliers')
        <x-buttons class="w-full sm:w-auto" onclick="createSupplier.showModal()">
          <x-heroicon-s-plus class="size-5"/>
          Create Supplier
        </x-buttons>
      @endcan
    </div>
    <x-tables :columnNames="$columns" :centeredColumns="[4,6]">
      <tbody class = "divide-y divide-gray-400">
          @foreach($suppliers as $supplier)
            <tr>
              <th class = "p-3 text-center">{{ $supplier->id }}</th>
              <x-td>{{ $supplier->name}}</x-td>
              <x-td>{{ $supplier->contact_person}}</x-td>
              <x-td>{{ $supplier->email}}</x-td>
              <x-td class="text-center">{{ $supplier->phone_number}}</x-td>
              <x-td class="whitespace-normal">{{ $supplier->address}}</x-td>
              <td class = "flex flex-col sm:flex-row gap-2 sm:gap-4 justify-center">
                @can("manage suppliers")
                  <x-buttons onclick="editSupplier.showModal()"
                    class="editBtn tooltip tooltip-top"
                    data-tip="Edit"
                    aria-label="Edit Supplier"
                    data-supplier="{{ json_encode([
                      'name'=>$supplier->name,
                      'contact_person'=>$supplier->contact_person,
                      'email'=>$supplier->email,
                      'phone_number'=>$supplier->phone_number,
                      'address'=>$supplier->address,
                      'route'=>route('suppliers.update', $supplier->id)], JSON_HEX_QUOT | JSON_HEX_APOS)}}">
                    <x-heroicon-o-pencil-square class="size-3 sm:size-5" />
                  </x-buttons>
                  <x-buttons onclick="deleteSupplier.showModal()"
                    class="deleteBtn bg-red-700 tooltip tooltip-top"
                    data-tip="Delete"
                    aria-label="Delete Supplier"
                    data-route="{{ route('suppliers.delete', $supplier->id) }}">
                    <x-heroicon-s-trash class="size-3 sm:size-5"/>
                  </x-buttons>
                @endcan
              </td>
            </tr>
          @endforeach
        </tbody>
    </x-tables>
    <div class = "text-base-content">
      {{ $suppliers->links() }}
    </div>
  </div>
</div>

@include('modals.supplier-modals.create-supplier-modal')
@include('modals.supplier-modals.edit-supplier-modal')
@include('modals.supplier-modals.delete-supplier-modal')
@endsection

@section('scripts')
  @vite('resources/js/supplier/delete-supplier.js')
  @vite('resources/js/supplier/edit-supplier.js')
@endsection