<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Workorder;

class WorkordersController extends Controller
{
    public function getWorkOrders(){
        $query = Workorder::with('request', 'disposalWorkOrder', 'serviceWorkorder', 'acquisitionWorkorder');
        $columns = ["Workorder Code", "Request Code", "Type", "Priority", "Start Date", "End Date", "Status", "Actions"];
        $workorders = $query->paginate(5);
        return view('pages.workorders.index-workorders', compact('workorders', 'columns'));
    }
}
