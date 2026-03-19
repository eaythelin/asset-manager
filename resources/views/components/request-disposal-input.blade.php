<div class="form-row">
    <x-page-label for="quantity" :required="true">Quantity</x-page-label>
    <x-page-input id="quantity" name="quantity" value="{{ old('quantity', $requestModel->quantity  ?? 1) }}" type="number"/>
</div>