<div class="form-row">
    <x-page-label for="asset_id">Asset Name</x-page-label>
    <x-page-select name="asset_id" id="asset_id">
        <option value="" disabled {{ old('asset_id', $selected) ? '' : 'selected' }}>--Select Asset--</option>
        @foreach($assets as $asset)
            <option value="{{ $asset->id }}" {{ old('asset_id', $selected) == $asset->id ? 'selected' : '' }}>
                {{ $asset->asset_code }} - {{ $asset->name }}
                @if($asset->serial_name)
                    ({{ $asset->serial_name }})
                @endif
            </option>
        @endforeach
    </x-page-select>
</div>