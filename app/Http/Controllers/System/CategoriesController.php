<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    public function getCategories(Request $request){
        $search = $request->input('search');
        $categories = Category::search($search)->paginate(5);
        $columns = ["", "Category Name", "Description", "Actions"];
        return view('pages.categories', compact('categories', "columns"));
    }

    public function storeCategory(Request $request){
        $validated = $request->validate([
            "name" => ["required", "string", "max:100", "unique:categories,name"],
            "description" => ["nullable","string", "max:255"]
        ]);

        Category::create($validated);

        return back()->with('success', 'New Category successfully created!');
    }

    public function updateCategory(Request $request, $id){
        $validated = $request->validate([
            "name" => ["required", "string", "max:100", Rule::unique('categories', 'name')->ignore($id)],
            "description" => ["nullable","string", "max:255"]
        ]);

        $category = Category::findOrFail($id);
        $category->update($validated);

        return back()->with('success', 'Category edited successfully!');
    }

    public function deleteCategory($id){
        $category = Category::findOrFail($id);

        if($category->subCategories()->exists()){
            return back()->with('error', 'Category is linked to an existing Subcategory!');
        }

        $category->delete();
        return redirect()->route('category.index')->with('success', 'Category successfully deleted!');
    }
}
