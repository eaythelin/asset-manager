<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function getActivityLogPage(){
        $query = ActivityLog::query();
        $activityLogs = $query->latest()->paginate(10);

        return view('pages.activitylogs', compact('activityLogs'));
    }
}
