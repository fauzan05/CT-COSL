<?php

namespace App\Http\Controllers;

use App\Models\ToolstringCategoryModel;
use App\Models\ToolstringItemModel;
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

    public function getItems()
    {
        // Retrieve all toolstring items
        $items = ToolstringItemModel::with('category')->get();

        // Return the items
        return response()->json($items);
    }

    public function storeItem(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'toolstring_category_id' => 'required|exists:toolstring_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|file|image|max:3072',
            'manufacturer' => 'nullable|string',
            'outer_diameter' => 'nullable|numeric',
            'inner_diameter' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'comment' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('image')) {
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/assets/images/toolstring_items/', $filename);

            // Simpan hanya nama file
            $validatedData['image'] = $filename;
        }

        // Create
        $item = ToolstringItemModel::create($validatedData);

        return response()->json($item, 201);
    }
}
