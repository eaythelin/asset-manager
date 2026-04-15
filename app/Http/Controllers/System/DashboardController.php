<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Department;
use App\Models\Category;
use App\Models\Asset;
use App\Models\Workorder;
use App\Models\Request as RequestModel;

class DashboardController extends Controller
{
    //
    public function getDashboard(){

        //The Assets per department are hidden if role = Department Head
        $role = Auth::user()->getRoleNames()->first();
        $userDepartment = auth()->user()->employee->department->id;

        $gridNumber = $role === "Department Head" ? "md:grid-cols-2" : "md:grid-cols-3";
        $toggleTable = $role === "Department Head" ? "hidden" : "block";
        $cardGridNumber = $role === 'System Supervisor' ? 'md:grid-cols-4' : 'md:grid-cols-5';

        //Get Departments
        $departments = Department::with("assets")->get();
        $categories = Category::orderBy('name')->pluck('name', 'id');

        //checking for overdue and expired
        Asset::where('status', '!=', 'disposed')
            ->where('is_depreciable', true)
            ->whereNotNull('end_of_life_date')
            ->get()
            ->each(fn($asset) => $asset->computed_status);
        
        Workorder::whereNotNull('end_date')
            ->get()
            ->each(fn($workorder) => $workorder->check_status);

        //asset numbers for the cards
        $activeAssetQuery = Asset::where('status', 'active');
        $disposeAssetsQuery = Asset::withTrashed()->where('status', 'disposed');
        $serviceAssetQuery = Asset::where('status','under_service');
        $expiredAssetQuery = Asset::where('status','expired');
        
        if(auth()->user()->getRoleNames()->contains('Department Head')){
            $activeAssetQuery->where('department_id', $userDepartment);
            $disposeAssetsQuery->where('department_id', $userDepartment);
            $serviceAssetQuery->where('department_id', $userDepartment);
            $expiredAssetQuery->where('department_id', $userDepartment);
        }

        $activeAssets = $activeAssetQuery->count();
        $disposedAssets = $disposeAssetsQuery->count();
        $serviceAssets = $serviceAssetQuery->count();
        $expiredAssets = $expiredAssetQuery->count();
        $pendingWO = Workorder::where('status', 'pending')->count();
        $inProgWO = Workorder::where('status', 'in_progress')->count();
        $overdueWO = Workorder::where('status','overdue')->count();
        $completedWO = Workorder::where('status', 'completed')->count();

        $requestCount = RequestModel::where('status', 'pending');
        if(auth()->user()->getRoleNames()->contains('General Manager')){
            $requestCount = $requestCount->count();
        }elseif(auth()->user()->getRoleNames()->contains('Department Head')){
            $requestCount = $requestCount->where('department_id', $userDepartment)->count();
        }else{
            $requestCount = 0;
        }

        //Column names for Filter Subcategory by Category and Assets per Department
        $subcategoryFilterColumns = ["", "Subcategory", "Count"];
        $assetsPerDepartmentColumns = ["", "Department", "Count"];
        return view("pages.dashboard", compact("gridNumber", "toggleTable", "departments", 
                                                            "subcategoryFilterColumns", "assetsPerDepartmentColumns", "categories",
                                                            "activeAssets", "disposedAssets","role", 'pendingWO', 'cardGridNumber',
                                                            "serviceAssets", "expiredAssets", 'inProgWO', 'overdueWO', 'completedWO',
                                                            "requestCount"));
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
