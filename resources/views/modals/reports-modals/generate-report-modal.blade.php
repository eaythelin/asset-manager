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

      <div x-show="selectedType === 'disposal'" class="flex flex-col gap-2">
        <x-label for="asset">Asset</x-label>
        <select name="asset" id="asset" class="select w-full rounded-xl">
          <option value="" disabled selected>--Select Asset</option>
          @foreach ($assets as $asset)
            <option value="{{ $asset->id }}">{{ $asset->asset_code }} {{  $asset->name  }}
              @if($asset->serial_name)
                ({{ $asset->serial_name }})
              @endif</option>
          @endforeach
        </select>
        <label class="font-medium">Disposal Date Range</label>
        <x-label for="disposal_date_from" class="text-sm">Date From</x-label>
        <input class = "input w-full rounded-xl" type="date" name="disposal_date_from" id="disposal_date_from">
        <x-label for="disposal_date_to" class="text-sm">Date To</x-label>
        <input class = "input w-full rounded-xl" type="date" name="disposal_date_to" id="disposal_date_to">
        <x-label for="disposal_method">Disposal Method</x-label>
        <select name="disposal_method" id="disposal_method" class="select w-full rounded-xl">
          <option value="" disabled selected>--Select Disposal Method</option>
          @foreach ($disposalMethods as $method)
            <option value="{{ $method->value }}">{{ $method->label() }}</option>
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

      <div x-show="selectedType !== 'disposal' && selectedType !== ''" class="flex flex-col gap-2">
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
