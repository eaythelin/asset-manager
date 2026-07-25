<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Enums\ActivityModule;

class ActivityLogController extends Controller
{
    public function getActivityLogPage(Request $request){
        $query = ActivityLog::query();
        $activityModules = ActivityModule::cases();

        if(request('module')){
          $query->where('module', request('module'));
        }

        if(request('search')){
            $search = $request->input('search');
            $query->search($search);
        }

        if(request('expand')){
          $activityLogs = $query->latest()->paginate(20)->withQueryString();
        }else{
          $activityLogs = $query->latest()->paginate(10)->withQueryString();
        }

        $columns = ["Date", "User", "Module", "Action", "Description"];

        return view('pages.activitylogs', compact('activityLogs', 'columns', 'activityModules'));
    }
}
