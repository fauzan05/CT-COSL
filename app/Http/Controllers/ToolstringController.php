<?php

namespace App\Http\Controllers;

use App\Models\ToolstringCategoryModel;
use Illuminate\Http\Request;

class ToolstringController extends Controller
{
    public function storeCategory(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $slug = str($request->name)
            ->slug()
            ->lower();

        // Create a new category
        $category = ToolstringCategoryModel::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        // Return the created category
        return response()->json($category, 201);
    }

    public function getCategories()
    {
        // Retrieve all categories
        $categories = ToolstringCategoryModel::all();

        // Return the categories
        return response()->json($categories);
    }

    public function updateCategory(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Find the category by ID
        $category = ToolstringCategoryModel::findOrFail($id);

        // Update the category
        $category->name = $request->name;
        $category->slug = str($request->name)->slug()->lower();
        $category->save();

        // Return the updated category
        return response()->json($category);
    }
    
    public function deleteCategory($id)
    {
        // Find the category by ID
        $category = ToolstringCategoryModel::findOrFail($id);

        // Delete the category
        $category->delete();

        // Return a success response
        return response()->json(['message' => 'Category deleted successfully'], 204);
    }
}
