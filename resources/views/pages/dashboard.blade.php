@extends('layouts.pageslayout')
@section('content')
<!-- Header -->
<x-pages-header title="Dashboard" description="Monitor fixed assets and system activity">
  <x-heroicon-s-home class="text-blue-800 size-8 md:size-10" />
</x-pages-header>

<x-toast-success />

<!--show unauthorized error-->
<x-session-error />

<!--Cards-->
<div class ="grid grid-cols-2 md:grid-cols-4 gap-4 md:mx-6 p-3 bg-white rounded-2xl shadow-xl">
  <x-dashboard-cards bgColor="bg-green-500" title="Active Assets" :number="$activeAssets">
    <x-heroicon-s-clipboard-document-check class="size-8 md:size-10"/>
  </x-dashboard-cards>
  <x-dashboard-cards bgColor="bg-red-500" title="Disposed Assets" :number="$disposedAssets">
    <x-heroicon-s-trash class="size-8 md:size-10"/>
  </x-dashboard-cards>
  <x-dashboard-cards bgColor="bg-gray-800" title="Assets Under Service" :number="0">
    <x-heroicon-s-wrench-screwdriver class="size-8 md:size-10"/>
  </x-dashboard-cards>
  <x-dashboard-cards bgColor="bg-orange-500" title="Expired Assets" :number="0">
    <x-heroicon-s-exclamation-triangle class="size-8 md:size-10" />
  </x-dashboard-cards>
</div>

<!--The Assets per department are hidden if role = Department Head-->
<!--Middle Charts-->
<div class="grid grid-cols-1 {{ $gridNumber }} gap-4 md:mx-6 mt-6">
  <!--Chart for Category-->
  <div class = "bg-white p-4 rounded-2xl shadow-xl">
    <div id="chartLoading" class="text-center py-8 mt-8">
      <span class="loading loading-spinner loading-lg"></span>
      <p class="text-gray-500 mt-2">Loading chart...</p>
    </div>
    <canvas id="categoryChart"  class="hidden"></canvas>
  </div>
  <div class = "bg-white p-4 rounded-2xl shadow-xl">
    <!--Chart for Subcategory(which is filtered)-->
    <h3 class="text-base font-bold text-gray-800 mb-4 text-center">Filter Subcategory by Category</h3>
    <div class="flex flex-row justify-between items-center mb-4 m-2">
      <label for="category" class="font-semibold text-sm md:text-base text-gray-700 flex items-center">
        <x-heroicon-s-funnel class="w-5 h-5 text-blue-800 mr-1" />
        Select Category</label>
      <select id="category" name="category" class="border-2 border-gray-300 p-1 w-1/2 bg-white shadow-sm hover:border-blue-500 focus:border-blue-600 focus:ring-2 focus:ring-blue-200 focus:outline-none transition-all duration-200 cursor-pointer text-gray-700 font-medium">
        <option class="text-sm" value="" disabled>Select Category</option>
        @foreach($categories as $id=>$name)
          <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
      </select>
    </div>
    <x-tables :columnNames="$subcategoryFilterColumns" class="max-h-73">
      <tbody id="subcategoryTable" class="divide-y divide-gray-400">
          <!--Populated by the javascript!!-->
      </tbody>
    </x-tables>
  </div>
  <!--Table for Assets per Department!!-->
  <div class = "bg-white p-4 rounded-2xl shadow-xl {{ $toggleTable }}">
    <h3 class="text-base font-bold text-gray-800 mb-4 text-center">Assets per Department</h3>
    <x-tables :columnNames="$assetsPerDepartmentColumns" class="max-h-73">
      <tbody class = "divide-y divide-gray-400">
          @foreach($departments as $department)
            <tr>
              <th class="p-3 text-center">{{ $department -> id }}</th>
              <td class="p-3">{{ $department -> name}}</td>
              <td class="p-3">{{ $department->assets->count() }}</td>
            </tr>
          @endforeach
        </tbody>
    </x-tables>
  </div>
</div>
@endsection

@section('scripts')
    @vite('resources/js/dashboard/categorypiechart.js')
    @vite('resources/js/dashboard/subcategory.js')
@endsection