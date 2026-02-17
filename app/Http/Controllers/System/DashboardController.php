<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Department;
use App\Models\Category;
use App\Models\Asset;

class DashboardController extends Controller
{
    //
    public function getDashboard(){

        //The Assets per department are hidden if role = Department Head
        $role = Auth::user()->getRoleNames() -> first();
        $userDepartment = auth()->user()->employee->department->id;

        $gridNumber = $role === "Department Head" ? "md:grid-cols-2" : "md:grid-cols-3";
        $toggleTable = $role === "Department Head" ? "hidden" : "block";

        //Get Departments
        $departments = Department::with("assets")->get();
        $categories = Category::orderBy('name')->pluck('name', 'id');

        //asset numbers for the cards
        $activeAssetQuery = Asset::where('status', 'active');
        if(auth()->user()->getRoleNames()->contains('Department Head')){
            $activeAssetQuery->where('department_id', $userDepartment);
        }
        $activeAssets = $activeAssetQuery->count();

        $disposeAssetsQuery = Asset::withTrashed()->where('status', 'disposed');
        if(auth()->user()->getRoleNames()->contains('Department Head')){
            $disposeAssetsQuery->where('department_id', $userDepartment);
        }
        $disposedAssets = $disposeAssetsQuery->count();

        //Column names for Filter Subcategory by Category and Assets per Department
        $subcategoryFilterColumns = ["", "Subcategory", "Count"];
        $assetsPerDepartmentColumns = ["", "Department", "Count"];
        return view("pages.dashboard", compact("gridNumber", "toggleTable", "departments", 
                                                            "subcategoryFilterColumns", "assetsPerDepartmentColumns", "categories",
                                                            "activeAssets", "disposedAssets"));
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
