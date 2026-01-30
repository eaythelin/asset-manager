<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubCategoriesController extends Controller
{
    public function getSubCategories(Request $request){
        $query = SubCategory::with(['category']);
        if(request('search')){
            $search = $request->input("search");
            $query->search($search);
        }
        
        $subCategories = $query->paginate(5);
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $columns = ["", "Subcategory", "Category", "Description", "Actions"];
        return view('pages.subcategories', compact('subCategories', 'columns', 'categories'));
    }

    public function storeSubCategory(Request $request){
        $validated = $request->validate([
            'name' => ["required", "max:100", "string", "unique:sub_categories,name"],
            'category_id' => ["required", "exists:categories,id"],
            'description' => ["nullable", "max:255", "string"],
        ]);

        SubCategory::create($validated);

        return back()->with('success', 'Subcategory successfully created!');
    }

    public function updateSubCategory(Request $request, $id){
        $validated = $request->validate([
            'name' => ["required", "max:100", "string", Rule::unique('sub_categories', 'name')->ignore($id)],
            'category_id' => ["required", "exists:categories,id"],
            'description' => ["nullable", "max:255", "string"],
        ]);

        $subCategory = SubCategory::findOrFail($id);
        $subCategory->update($validated);

        return back()->with('success', 'Subcategory successfully updated!');
    }

    public function deleteSubCategory($id){
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->delete();

        return redirect()->route('subcategory.index')->with('success', 'Subcategory successfully deleted!');
    }
}
