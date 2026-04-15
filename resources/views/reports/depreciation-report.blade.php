@extends("layouts.reportlayout")
@section("content")

<div class="header">
    <p class="company-name">Master Coating Industrial Technology Incorporated</p>
    <p class="report-title">Depreciation Report</p>
    <p>Generated: {{ now()->format('M d, Y') }}</p>
</div>
<div class="divider"></div>

<table>
    <thead>     
        <tr>
            <th>Asset Code</th>
            <th>Asset Name</th>
            <th>Quantity</th>
            <th>Serial Name</th>
            <th>Department</th>
            <th>Category</th>
            <th>Acquired Date</th>
            <th>Useful Life (Yrs)</th>
            <th>End of Life Date</th>
            <th>Cost</th>
            <th>Salvage Value</th>
            <th>Accum. Deprec.</th>
            <th>Net Book Value</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $asset)
        <tr>
            <td>{{ $asset->asset_code }}</td>
            <td>{{ $asset->name }}</td>
            <td>{{ $asset->quantity }}</td>
            <td>{{ $asset->serial_name ?? 'N/A'}}</td>
            <td>{{ $asset->department->name }}</td>    
            <td>{{ $asset->category->name }}</td>
            <td>{{ $asset->acquisition_date?->format('F j, Y') }}</td>
            <td>{{ $asset->useful_life_in_years }}</td>
            <td>{{ $asset->end_of_life_date?->format('F j, Y') }}</td>
            <td>{{ number_format($asset->cost ?? 0, 2) }}</td>
            <td>{{ number_format($asset->salvage_value ?? 0, 2) }}</td>
            <td>{{ number_format($asset->accumulated_depreciation ?? 0, 2) }}</td>
            <td>{{ number_format($asset->book_value ?? 0, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection