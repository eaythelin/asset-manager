@extends("layouts.reportlayout")
@section("content")

<div class="header">
    <p class="company-name">Master Coating Industrial Technology Incorporated</p>
    <p class="report-title">Service Asset Report</p>
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
            <th>Serviced Quantity</th>
            <th>Service Type</th>
            <th>Maintenance Type</th>
            <th>Handled By</th>
            <th>Start Date</th>
            <th>End Date</th>
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
            <td>{{ $record->workorder->request->quantity }}</td>    
            <td>{{ $record->service_type->label() }}</td>
            <td>{{ $record->maintenance_type?->label() }}</td>
            <td>{{ $record->subcontractor_name ?? $record->assignedTo?->full_name }}</td>
            <td>{{ $record->workorder?->start_date?->format('F j, Y') }}</td>
            <td>{{ $record->workorder?->end_date?->format('F j, Y') }}</td>
            <td>{{ $record->workorder?->completedBy?->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection