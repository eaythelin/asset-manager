@extends("layouts.reportlayout")
@section("content")

<div class="header">
    <p class="company-name">Master Coating Industrial Technology Incorporated</p>
    <p class="report-title">Asset Report</p>
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
            <th>Custodian</th>
            <th>Category</th>
            <th>Status</th>
            <th>Acquistion Date</th>
            <th>Cost</th>
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
            <td>{{ $asset->custodian?->first_name}} {{ $asset->custodian?->last_name}}</td>
            <td>{{ $asset->category->name }}</td>
            <td>{{ $asset->status->label() }}</td>
            <td>{{ $asset->acquisition_date?->format('F j, Y') }}</td>
            <td>{{ number_format($asset->cost ?? 0, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection