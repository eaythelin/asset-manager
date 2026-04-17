<x-modal name="generateReport" title="Generate Report">
  <form method = "POST" action="{{ route('reports.generate') }}">
    <div class = "flex flex-col gap-3 px-2 sm:px-4" x-data="{ selectedType: '', selectedWOType: '' }">
      @csrf
      <x-label for="report_type" :required="true">Report Type </x-label>
      <select name = 'report_type' id="report_type" class="select w-full rounded-xl" x-model="selectedType">
        <option value="" disabled selected>--Select Report Type--</option>
        @foreach($reportTypes as $type)
          <option value="{{ $type->value }}">{{ $type->label() }}</option>
        @endforeach
      </select>

      <div x-show="selectedType === 'workorder'" class="flex flex-col gap-2">
        <x-label for="workorder_type" :required="true">Workorder Type</x-label>
        <select name="workorder_type" id="workorder_type" class="select w-full rounded-xl" x-model="selectedWOType">
          <option value="" disabled selected>--Select Workorder Type--</option>
          @foreach($workorderTypes as $type)
            <option value="{{ $type->value }}">{{ $type->label() }}</option>
          @endforeach
        </select>

        <div x-show="selectedWOType === 'disposal'" class="flex flex-col gap-2">
          <x-label for="disposal_method">Disposal Method</x-label>
          <select name="disposal_method" id="disposal_method" class="select w-full rounded-xl">
            <option value="" disabled selected>--Select Disposal Method--</option>
            @foreach($disposalMethods as $method)
              <option value="{{ $method->value }}">{{ $method->label() }}</option>
            @endforeach
          </select>

          <label class="font-medium">'Disposal Date Range</label>
          <x-label for="disposal_date_from" class="text-sm">Date From</x-label>
          <input class = "input w-full rounded-xl" type="date" name="disposal_date_from" id="disposal_date_from">
          <x-label for="disposal_date_to" class="text-sm">Date To</x-label>
          <input class = "input w-full rounded-xl" type="date" name="disposal_date_to" id="disposal_date_to">
        </div>

        <div x-show="selectedWOType === 'service'" class="flex flex-col gap-2">
          <label class="font-medium">Completion Date Range</label>
          <x-label for="service_date_from" class="text-sm">Date From</x-label>
          <input class = "input w-full rounded-xl" type="date" name="service_date_from" id="service_date_from">
          <x-label for="service_date_to" class="text-sm">Date To</x-label>
          <input class = "input w-full rounded-xl" type="date" name="service_date_to" id="service_date_to">

          <x-label for="service_type" class="text-sm">Service Type</x-label>
          <select name="service_type" id="service_type" class="select w-full rounded-xl">
            <option value="" disabled selected>--Select Service Type--</option>
            @foreach($serviceTypes as $type)
              <option value="{{ $type->value }}">{{ $type->label() }}</option>
            @endforeach
          </select>

          <x-label for="maintenance_type" class="text-sm">Maintenance Type</x-label>
          <select name="maintenance_type" id="maintenance_type" class="select w-full rounded-xl">
            <option value="" disabled selected>--Select Maintenance Type--</option>
            @foreach($maintenanceTypes as $type)
              <option value="{{ $type->value }}">{{ $type->label() }}</option>
            @endforeach
          </select>
        </div>

        <div x-show="selectedWOType === 'requisition'" class="flex flex-col gap-2">
          <x-label for="N/A" :required="true">Asset Filters</x-label>
          <div class="flex gap-3">
            <input type="radio" name="asset_filter" id="filter_all" value="all" class="radio radio-primary" checked>
            <label for="filter_all">All</label>
            <input type="radio" name="asset_filter" id="filter_new" value="new" class="radio radio-primary"> 
            <label for="filter_new">New Assets</label>
            <input type="radio" name="asset_filter" id="filter_old" value="old" class="radio radio-primary">
            <label for="filter_old">Existing Assets</label>
          </div>

          <label class="font-medium">Completion Date Range</label>
          <x-label for="comp_date_from" class="text-sm">Date From</x-label>
          <input class = "input w-full rounded-xl" type="date" name="comp_date_from" id="comp_date_from">
          <x-label for="comp_date_to" class="text-sm">Date To</x-label>
          <input class = "input w-full rounded-xl" type="date" name="comp_date_to" id="comp_date_to">

          <x-label for="supplier_id">Supplier</x-label>
          <select name="custodian_id" id="custodian_id" class="select w-full rounded-xl">
            <option value="" disabled selected>--Select Supplier--</option>
            @foreach($suppliers as $id=>$name)
              <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div x-show="selectedType === 'asset'" class="flex flex-col gap-2">
        <label class="font-medium">Acquisition Date Range</label>
        <x-label for="date_from" class="text-sm">Date From</x-label>
        <input class = "input w-full rounded-xl" type="date" name="date_from" id="date_from">
        <x-label for="date_to" class="text-sm">Date To</x-label>
        <input class = "input w-full rounded-xl" type="date" name="date_to" id="date_to">

        <x-label for="custodian_id">Custodian</x-label>
        <select name="custodian_id" id="custodian_id" class="select w-full rounded-xl">
          <option value="" disabled selected>--Select Custodian--</option>
          @foreach($employees as $id=>$name)
            <option value="{{ $id }}">{{ $name }}</option>
          @endforeach
        </select>
      </div>
      
      <div x-show="selectedType === 'asset' || selectedType === 'depreciation'" class="flex flex-col gap-2">
        <x-label for="status">Status</x-label>
        <select name="status" id="status" class="select w-full rounded-xl">
          <option value="" disabled selected>--Select Status--</option>
          @foreach($assetStatus as $status)
            <option value="{{ $status->value }}">{{ $status->label() }}</option>
          @endforeach
        </select>

        <x-label for="category_id">Category</x-label>
        <select name="category_id" id="category_id" class="select w-full rounded-xl">
          <option value="" disabled selected>--Select Category--</option>
          @foreach($categories as $id=>$name)
            <option value="{{ $id }}">{{ $name }}</option>
          @endforeach
        </select>
      </div>

      <div x-show="selectedType !== '' || selectedWOType !== ''" class="flex flex-col gap-2">
        <x-label for="department_id">Department</x-label>
        <select name="department_id" id="department_id" class="select w-full rounded-xl">
          <option value="" disabled selected>--Select Department--</option>
          @foreach($departments as $id=>$name)
            <option value="{{ $id }}">{{ $name }}</option>
          @endforeach
        </select>
      </div>

      <x-buttons class="mt-2" type="Submit">Submit</x-buttons>
    </div>
  </form>   
</x-modal>