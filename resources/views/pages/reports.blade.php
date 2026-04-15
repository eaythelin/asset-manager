@extends("layouts.pageslayout")
@section("content")
<x-pages-header title="Reports" description="View and generate reports">
  <x-heroicon-c-chart-bar-square class="text-blue-800 size-8 md:size-10"/>
</x-pages-header>

<x-toast-success />
<x-session-error />

<div class = "md:m-4">
  <x-validation-error />
  
  <div class = "bg-white p-4 rounded-2xl shadow-xl">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4 mx-2">

      <x-search-bar route="category.index" placeholder="Search reports..."/>
      
      @can('manage reports')
        <x-buttons class="w-full sm:w-auto" onclick="generateReport.showModal()">
          <x-heroicon-s-plus class="size-5"/>
          Create Report
        </x-buttons>
      @endcan
    </div>
    <x-tables :columnNames="$columns" :centeredColumns="[0,1,4]">
      <tbody class = "divide-y divide-gray-500">
          @foreach($reports as $report)
            <tr>
              <th class = "p-3 text-center">{{ $report->report_code}}</th>
              <x-td class="text-center">
                <span class="badge {{ $report->report_type->badgeClass() }} text-white font-medium text-sm">
                  {{ $report->report_type->label() }}
                </span>
              </x-td>
              <x-td>{{ $report->generatedBy->name }}</x-td>
              <x-td>{{ $report->created_at->format('M d, Y') }}</x-td>
              <td class = "flex flex-row gap-2 sm:gap-4 justify-center">
                @can("manage reports")
                  <a class="w-full sm:w-auto flex justify-center" href="{{ route('report.download', $report->id) }}">
                    <x-buttons
                      class="downloadBtn tooltip tooltip-top"
                      data-tip="Download"
                      aria-label="Download Report">
                      <x-heroicon-o-arrow-down-tray class="size-4 sm:size-5" />
                    </x-buttons>
                  </a>
                  <x-buttons onclick="deleteReport.showModal()"
                    class="deleteBtn bg-red-700 tooltip tooltip-top"
                    data-tip="Delete"
                    aria-label="Delete Report"
                    data-route="{{ route('report.delete', $report->id ) }}">
                    <x-heroicon-s-trash class="size-4 sm:size-5"/>
                  </x-buttons>
                @endcan
                </td>
              </tr>
          @endforeach
        </tbody>
    </x-tables>
    <div class = "text-base-content">
      {{ $reports->links() }}
    </div>
  </div>
</div>

@include('modals.reports-modals.generate-report-modal')
@include('modals.reports-modals.delete-report-modal')

@endsection

@section('scripts')
    @vite('resources/js/reports/deleteReport.js')
@endsection