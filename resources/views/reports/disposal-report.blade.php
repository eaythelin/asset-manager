@extends("layouts.reportlayout")
@section("content")

<div class="header">
    <p class="company-name">Master Coating Industrial Technology Incorporated</p>
    <p class="report-title">Disposal Asset Report</p>
    <p>Generated: {{ now()->format('M d, Y') }}</p>
</div>
<div class="divider"></div>

<table>
    <thead>     
        <tr>
            <th>Workorder Code</th>
            <th>Asset Code</th>
            <th>Asset Name</th>
            <th>Department</th>
            <th>Disposed Quantity</th>
            <th>Disposal Date</th>
            <th>Disposal Method</th>
            <th>Completed By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $record)
        <tr>
            <td>{{ $record->workorder->workorder_code }}</td>
            <td>{{ $record->asset->asset_code }}</td>
            <td>{{ $record->asset->name }}</td>
            <td>{{ $record->asset->department->name }}</td>
            <td>{{ $record->quantity }}</td>    
            <td>{{ $record->disposal_date?->format('F j, Y') }}</td>
            <td>{{ $record->disposal_method->label() }}</td>
            <td>{{ $record->workorder->completedBy->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection