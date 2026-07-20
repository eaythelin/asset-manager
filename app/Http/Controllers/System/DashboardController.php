<?php

namespace App\Http\Controllers\System;

use App\Enums\WorkorderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Department;
use App\Models\Category;
use App\Models\Asset;
use App\Models\ActivityLog;
use App\Models\Workorder;
use App\Models\Request as RequestModel;
use App\Enums\AssetStatus;

class DashboardController extends Controller
{
    //
    public function getDashboard(){

        //The Assets per department are hidden if role = Department Head
        $role = Auth::user()->getRoleNames()->first();
        $userDepartment = auth()->user()->employee->department->id;

        $gridNumber = $role === "Department Head" ? "md:grid-cols-2" : "md:grid-cols-3";
        $toggleTable = $role === "Department Head" ? "hidden" : "block";
        $cardGridNumber = $role === 'System Supervisor' ? 'md:grid-cols-3' : 'md:grid-cols-3';

        //Get Departments
        $departments = Department::with("assets")->get();
        $categories = Category::orderBy('name')->pluck('name', 'id');

        //asset numbers for the cards
        $activeAssetQuery = Asset::where('status', 'active');
        $disposeAssetsQuery = Asset::withTrashed()->where('status', 'disposed');
        $expiredAssetQuery = Asset::where('status','expired');
        $maintenanceAssetsQuery = Asset::where('status', AssetStatus::MAINTENANCE);
        $repairAssetsQuery = Asset::where('status', AssetStatus::REPAIR);
        $fabricationAssetsQuery = Asset::where('status', AssetStatus::FABRICATION);

        if(auth()->user()->getRoleNames()->contains('Department Head')){
            $activeAssetQuery->where('department_id', $userDepartment);
            $disposeAssetsQuery->where('department_id', $userDepartment);
            $expiredAssetQuery->where('department_id', $userDepartment);
            $maintenanceAssetsQuery->where('department_id', $userDepartment);
            $repairAssetsQuery->where('department_id', $userDepartment);
            $fabricationAssetsQuery->where('department_id', $userDepartment);
        }

        $activeAssets = $activeAssetQuery->count();
        $disposedAssets = $disposeAssetsQuery->count();
        $expiredAssets = $expiredAssetQuery->count();
        $maintenanceAssets = $maintenanceAssetsQuery->count();
        $repairAssets = $repairAssetsQuery->count();
        $fabricationAssets = $fabricationAssetsQuery->count();

        $getCount = function($status) use ($userDepartment) {
            $query = RequestModel::where('status', $status);

            if (auth()->user()->getRoleNames()->contains('General Manager')) {
                return $query->count();
            } elseif (auth()->user()->getRoleNames()->contains('Department Head')) {
                return $query->where('department_id', $userDepartment)->count();
            }

            return 0;
        };

        $pendingCount = $getCount('pending');
        $approvedCount = $getCount('approved');
        $declinedCount = $getCount('rejected');

        //activity log table
        $activityLogs = ActivityLog::latest()->take(5)->get();

        $activityColumns = ["Date", "User", "Module", "Action", "Description"];

        $pendingWO = Workorder::where('status', WorkorderStatus::PENDING->value)->count();
        $inProgWO = Workorder::where('status', WorkorderStatus::IN_PROGRESS->value)->count();
        $completedWO = Workorder::where('status', WorkorderStatus::COMPLETED->value)->count();

        //Column names for Filter Subcategory by Category and Assets per Department
        $subcategoryFilterColumns = ["", "Subcategory", "Count"];
        $assetsPerDepartmentColumns = ["", "Department", "Count"];
        return view("pages.dashboard", compact("gridNumber", "toggleTable", "departments",
                                                            "subcategoryFilterColumns", "assetsPerDepartmentColumns", "categories",
                                                            "activeAssets", "disposedAssets","role", 'cardGridNumber',"expiredAssets",
                                                            "maintenanceAssets", "repairAssets", "fabricationAssets", "activityColumns", "activityLogs",
                                                            "pendingWO", "inProgWO", "completedWO", "pendingCount", "approvedCount", "declinedCount"));
    }

    public function getSubcategoryCount(Category $category){
        $subcategories = $category->subCategories()->withCount("assets")->get();

        return response()->json($subcategories);
    }

    public function getChartData(){
        $categories = Category::withCount('assets')->get();

        return response()->json([
            'labels' => $categories->pluck('name'),
            'counts' => $categories->pluck('assets_count')
        ]);
    }
}
