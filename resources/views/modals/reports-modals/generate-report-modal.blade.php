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
