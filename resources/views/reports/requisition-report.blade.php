@extends("layouts.reportlayout")
@section("content")

<div class="header">
    <p class="company-name">Master Coating Industrial Technology Incorporated</p>
    <p class="report-title">Requisition Asset Report</p>
    <p>Generated: {{ now()->format('M d, Y') }}</p>
</div>
<div class="divider"></div>

<table>
    <thead>     
        <tr>
            <th>Workorder Code</th>
            <th>Asset Code</th>
            <th>Asset Name</th>
            <th>Category</th>
            <th>Department</th>
            <th>Quantity</th>
            <th>Supplier</th>
            <th>Estimated Cost</th>
            <th>Completion Date</th>
            <th>Completed By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $record)
        <tr>
            <td>{{ $record->workorder->workorder_code }}</td>
            <td>{{ $record->asset->asset_code }}</td>
            <td>{{ $record->asset->name }}</td>
            <td>{{ $record->asset->category->name }}</td>
            <td>{{ $record->asset->department->name }}</td>
            <td>{{ $record->workorder->request->quantity }}</td>    
            <td>{{ $record->supplier?->name ?? 'N/A'}}</td>
            <td>{{ $record->estimated_cost ? number_format($record->estimated_cost, 2) : '—' }}</td>
            <td>{{ $record->workorder?->end_date?->format('F j, Y') }}</td>
            <td>{{ $record->workorder?->completedBy?->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection