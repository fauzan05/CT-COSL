<?php

namespace App\Http\Controllers;

use App\Models\WellstackTypeModel;
use Illuminate\Http\Request;

class WellstackController extends Controller
{
    public function getTypes()
    {
        $types = WellstackTypeModel::orderBy('name', 'asc')->get();

        return response()->json($types);
    }

    public function storeType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $slug = str($request->name)
            ->slug()
            ->lower();

        $type = new WellstackTypeModel($validated);
        $type->slug = $slug;
        $type->created_by = $request->user()->id ?? null; // Assuming you have user authentication
        $type->updated_by = $request->user()->id ?? null; // Assuming you have user authentication
        $type->save();

        return response()->json(['message' => 'Wellstack type created successfully', 'type' => $type], 201);
    }

    public function updateType(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        // Find the type by ID
        $type = WellstackTypeModel::findOrFail($id);

         // Update the type
         $type->name = $request->name;
         $type->slug = str($request->name)->slug()->lower();
         $type->updated_by = $request->user()->id; // Assuming the user is authenticated
         $type->save();

        return response()->json(['message' => 'Wellstack type updated successfully', 'type' => $type], 200);
    }

    public function deleteType($id)
    {
        $type = WellstackTypeModel::findOrFail($id);
        $type->delete();

        return response()->json(['message' => 'Wellstack type deleted successfully'], 204);
    }
}
