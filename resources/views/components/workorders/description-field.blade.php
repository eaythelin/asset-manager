<div class="form-row">
    <x-page-label for="description">Description</x-page-label>
    <x-page-textarea name="description" id="description">{{ old('description', $value ?? '') }}</x-page-textarea>
</div>