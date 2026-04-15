<div class="form-row">
    <x-page-label for="supplier_id">Supplier</x-page-label>
    <x-page-select name="supplier_id" id="supplier_id">
    <option value="" disabled selected>--Select Supplier--</option>
    @foreach($suppliers as $id=>$name)
        <option value="{{ $id }}" 
        {{ old('supplier_id', $workorder ?? '') == $id ? 'selected' : '' }}>
        {{ $name }}
        </option>
    @endforeach
    </x-page-select>
</div>