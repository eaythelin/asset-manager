@extends('layouts.pageslayout')
@section('content')

<div class = "md:mx-4">
  <div class = "mb-4">
    <x-back-link route="workorders.index">Return to Workorders</x-back-link>
  </div>
	
	<x-view-page-header-card>
		<div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
			<div class="flex items-start gap-4">
				<div class="hidden sm:flex items-center justify-center size-12 rounded-xl bg-indigo-50 text-indigo-600">
					<x-heroicon-s-clipboard-document class="size-6"/>
				</div>

				<div>
					<div class="flex items-center gap-2 mb-1">
						<span class="text-xs font-bold uppercase tracking-wider text-gray-400">Workorder Code</span>
						<span class="text-xs py-0.5 px-2 bg-gray-100 text-gray-600 rounded-md font-mono">
							#{{ $workorder->workorder_code }}
						</span>
					</div>
					<h2 class="text-xl font-bold text-gray-900 leading-tight">
						{{ $workorder->workorder_type->label() }}
					</h2>
				</div>
			</div>

			<div class="flex flex-col items-end gap-2">
				<span class="badge {{ $workorder->status->badgeClass() }} text-white font-medium text-sm">
					{{ $workorder->status->label() }}
				</span>
				<span class="text-xs text-gray-400 flex items-center gap-1">
					<x-heroicon-c-clock class="size-3"/>
					Updated {{ $workorder->updated_at->diffForHumans() }}
				</span>
			</div>
		</div>
	</x-view-page-header-card>

	<div role="tablist" class="tabs tabs-lifted">
		{{-- Overview Tab --}}
		<input type="radio" name="request_tabs" role="tab" class="tab" aria-label="Overview" checked />
    <div role="tabpanel" class="tab-content bg-base-200 border-base-300 rounded-box p-6">
			@if($workorder->request->is_new_asset)
				<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div class="bg-white p-4 rounded-2xl shadow-xl">
            <div class="flex flex-row items-center gap-2 mb-2">
              <x-heroicon-s-document-text class="size-6 text-green-700"/>
              <p class="text-lg font-semibold">Workorder Details</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
							<div class="space-y-3">
								<x-detail-item label="Priority" :value="$workorder->priority_level->label()"/>
								<x-detail-item label="Start Date" :value="$workorder->start_date?->format('F j, Y')"/>
								<x-detail-item label="End Date" :value="$workorder->end_date?->format('F j, Y')"/>
							</div>
							<div class="space-y-3">
								<x-detail-item label="Linked Request Code" :value="$workorder->request?->request_code"/>
								<x-detail-item label="Completed By" :value="$workorder->completedBy?->name" />
							</div>
						</div>
          </div>

          <div class="bg-white p-4 rounded-2xl shadow-xl">
            <div class="flex flex-row items-center gap-2 mb-2">
              <x-heroicon-s-archive-box class="size-6 text-green-700"/>
              <p class="text-lg font-semibold">Requisition Details</p> 
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="space-y-3">
                <x-detail-item label="Asset Name" :value="$workorder->requisitionWorkorder?->asset_name" />
                <x-detail-item label="Category" :value="$workorder->request?->category->name" />
                <x-detail-item label="Subcategory" :value="$workorder->request?->subCategory?->name" />
                <x-detail-item label="Quantity" :value="$workorder->request?->quantity" />
              </div>

              <div class="space-y-3">
                <x-detail-item label="Acquistion Date" :value="$workorder->requisitionWorkorder?->acquisition_date->format('F j, Y')" />
                <x-detail-item label="Estimated Cost" :value="'₱'. $workorder->requisitionWorkorder?->estimated_cost" />
                <x-detail-item label="Supplier" :value="$workorder->requisitionWorkorder?->supplier->name" />
                <x-detail-item label="Description" :value="$workorder->requisitionWorkorder?->description" />
              </div>
            </div>
          </div>
        </div>
			@else
				<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
					<div class="bg-white p-4 rounded-2xl shadow-xl">
						<div class="flex flex-row items-center gap-2 mb-2">
              <x-heroicon-s-information-circle class="size-6 text-blue-700"/>
              <p class="text-lg font-semibold">Asset Details</p>
            </div>

						<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
							<div class="size-32 shrink-0 mx-auto">
								@if($workorder->request->asset?->image_path)
									<img src="{{ Storage::url($workorder->request->asset->image_path) }}" alt="{{ $workorder->request->asset->image_path }}" class="w-full h-full object-cover rounded-lg shadow-xl">
								@else
									<div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-photo class="size-16 text-gray-400" />
                  </div>
								@endif
							</div>
							<div class="space-y-3">
								<x-detail-item label="Asset Code" :value="$workorder->request->asset?->asset_code"/>
                <x-detail-item label="Asset Name" :value="$workorder->request->asset?->name"/>
                <x-detail-item label="Serial Name" :value="$workorder->request->asset?->serial_name"/>
              </div>
							<div class="space-y-3">
                <x-detail-item label="Department" :value="$workorder->request->asset?->department->name"/>
                <x-detail-item label="Category" :value="$workorder->request->asset?->category->name"/>
                <x-detail-item label="Current Quantity" :value="$workorder->request->asset?->quantity"/>
              </div>
						</div>
					</div>

					<div class="bg-white p-4 rounded-2xl shadow-xl">
						<div class="flex flex-row items-center gap-2 mb-2">
              <x-heroicon-s-document-text class="size-6 text-green-700"/>
              <p class="text-lg font-semibold">Workorder Details</p>
            </div>
						<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
							<div class="space-y-3">
								<x-detail-item label="Priority" :value="$workorder->priority_level->label()"/>
								<x-detail-item label="Start Date" :value="$workorder->start_date?->format('F j, Y')"/>
								<x-detail-item label="End Date" :value="$workorder->end_date?->format('F j, Y')"/>
							</div>
							<div class="space-y-3">
								<x-detail-item label="Linked Request Code" :value="$workorder->request?->request_code"/>
								<x-detail-item label="Completed By" :value="$workorder->completedBy?->name" />
							</div>
						</div>	
					</div>
				</div>
				
				@if($workorder->workorder_type->value === 'disposal')
					<div class="bg-white p-4 rounded-2xl shadow-xl">
						<div class="flex flex-row items-center gap-2 mb-2">
							<x-heroicon-s-archive-box class="size-6 text-red-700"/>
							<p class="text-lg font-semibold">Disposal Details</p>
						</div>

						<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
							<div class="space-y-3">
								<x-detail-item label="Quantity" :value="$workorder->disposalWorkorder?->quantity" />
								<x-detail-item label="Disposal Method" :value="$workorder->disposalWorkOrder?->disposal_method?->label()"/>
							</div>
							<div class="space-y-3">
								<x-detail-item label="Disposal Date" :value="$workorder->disposalWorkorder?->disposal_date?->format('F j, Y')" />
								<x-detail-item label="Reason" :value="$workorder->disposalWorkOrder?->reason"/>
							</div>
						</div>
					</div>
				@elseif($workorder->workorder_type->value === 'service')
					<div class="bg-white p-4 rounded-2xl shadow-xl">
						<div class="flex flex-row items-center gap-2 mb-2">
							<x-heroicon-s-wrench-screwdriver class="size-6 text-gray-700"/>
							<p class="text-lg font-semibold">Service Details</p>
						</div>

						<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
							<div class="space-y-3">
								<x-detail-item label="Service Type" :value="$workorder->request->service_type?->label()"/>
								<x-detail-item label="Maintenance Type" :value="$workorder->serviceWorkorder?->maintenance_type?->label()"/>
								<x-detail-item label="Quantity" :value="$workorder->request?->quantity" />
							</div>

							@if($workorder->serviceWorkorder?->maintenance_type?->value === 'subcontractor')
								<div class="space-y-3">
                  <x-detail-item label="Cost" :value="$workorder->serviceWorkorder?->cost" />
									<x-detail-item label="Subcontractor Name" :value="$workorder->serviceWorkorder?->subcontractor_name"/>
									<x-detail-item label="Subcontractor Details" :value="$workorder->serviceWorkorder?->subcontractor_details"/>
								</div>
							@elseif($workorder->serviceWorkorder?->maintenance_type?->value === 'in_house')
								<div class="space-y-3">
                  <x-detail-item label="Cost" :value="$workorder->serviceWorkorder?->cost" />
									<x-detail-item label="Assigned To" :value="$workorder->serviceWorkorder?->assignedTo?->first_name . ' ' . $workorder->serviceWorkorder?->assignedTo?->last_name"/>
									<x-detail-item label="Estimated Hours" :value="$workorder->serviceWorkorder?->estimated_hours"/>
								</div>
							@endif

							<div class="space-y-3">
								<x-detail-item label="Instructions" :value="$workorder->serviceWorkorder?->instructions" />
								<x-detail-item label="Accomplishment Report" :value="$workorder->serviceWorkorder?->accomplishment_report"/>
							</div>
						</div>
					</div>
        @elseif($workorder->workorder_type->value === 'requisition' && !$workorder->request->is_new_asset)
          <div class="bg-white p-4 rounded-2xl shadow-xl">
            <div class="flex flex-row items-center gap-2 mb-2">
							<x-heroicon-s-archive-box class="size-6 text-green-700"/>
							<p class="text-lg font-semibold">Requisition Details</p>
						</div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="space-y-3">
                <x-detail-item label="Quantity" :value="$workorder->request?->quantity"/>
                <x-detail-item label="Supplier" :value="$workorder->requisitionWorkorder?->supplier?->name"/>
              </div>

              <div class="space-y-3">
                <x-detail-item label="Description" :value="$workorder->requisitionWorkorder?->description"/>
              </div>
            </div>
          </div>
				@endif
			@endif
		</div>
	</div>
</div>
@endsection