@extends("layouts.pageslayout")
@section("content")

<x-pages-header title="Activity Logs" description="Monitor system activity">
  <x-heroicon-s-clock class="text-blue-800 size-8 md:size-10" />
</x-pages-header>

<div class="md:m-4">
  <div class="bg-white p-4 rounded-2xl shadow-2xl">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4 mx-2">
      <div class="flex flex-col sm:flex-row gap-5 w-full sm:w-auto">
        <form method = "GET" action="{{ route('activitylog.index' ) }}">
          <div class = "flex flex-row gap-3">
              <input type="text" placeholder="Search activity logs..." class="input input-bordered w-full" name="search" value="{{ request('search') }}" />

              <select name="module" class="select border-2 border-gray-300">
                <option value="">All Modules</option>
                @foreach($activityModules as $module)
                  <option value="{{ $module->value }}" {{ request('module') == $module->value ? 'selected' : '' }}>{{ $module->label() }}</option>
                @endforeach
              </select>
              <x-buttons type="submit">Search</x-buttons>
          </div>
        </form>
      </div>

      <form method="GET" action="{{ route('activitylog.index') }}">
        <input type="hidden" name="search" value="{{ request('search') }}">
        <label class="flex mt-3 items-center gap-2 cursor-pointer">
          <input type="checkbox" name="expand" class="checkbox" {{ request('expand') ? 'checked' : '' }}
            onchange="this.form.submit()">
          <span class="font-medium">Expand</span>
        </label>
      </form>
    </div>

    <x-tables :columnNames="$columns" :centeredColumns="[0]">
      <tbody class="divide-y divide-gray-400">
          @foreach($activityLogs as $log)
            <tr>
              <th class="p-3 text-center">{{ $log->created_at->format('M d, Y h:i A') }}</th>
              <x-td>{{ $log->user->name }}</x-td>
              <x-td>{{ $log->module->label() }}</x-td>
              <x-td>{{ $log->action->label() }}</x-td>
              <x-td>{{ $log->description }}</x-td>
            </tr>
          @endforeach
        </tbody>
    </x-tables>
    <div class = "text-base-content">
      {{ $activityLogs->links() }}
    </div>
  </div>
</div>
@endsection
