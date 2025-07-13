<?php

namespace App\Http\Controllers;

use App\Models\WellstackItemModel;
use App\Models\WellstackReportingHistoryModel;
use App\Models\WellstackTypeModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WellstackController extends Controller
{
    public function getTypes()
    {
        $types = WellstackTypeModel::orderBy('name', 'asc')->get();

        return response()->json($types);
    }

    public function getType($id)
    {
        $type = WellstackTypeModel::findOrFail($id);

        return response()->json($type);
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

    public function storeItem(Request $request)
    {
        DB::transaction(function () use ($request) {
            // Validate input
            $validatedData = $request->validate([
                'wellstack_type_id' => 'required|exists:wellstack_types,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|file|image|max:3072',
                'serial_number' => 'nullable|string|max:255',
                'height' => 'nullable|numeric',
                'height_unit' => 'nullable|string|max:10',
                'weight' => 'nullable|numeric',
                'weight_unit' => 'nullable|string|max:10',
                'pressure_rating' => 'nullable|numeric',
                'pressure_rating_unit' => 'nullable|string|max:10',
                'owner' => 'nullable|string|max:255',
                'shear_ram_dist_from_bottom' => 'nullable|numeric',
                'shear_ram_dist_from_bottom_unit' => 'nullable|string|max:10',
            ]);

            // Handle file upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();

                $filename = Str::random(40) . '.' . $extension;

                $image->storeAs('public/assets/images/wellstack_items/', $filename);

                // Simpan hanya nama file
                $validatedData['image'] = $filename;
            }

            // Set created_by and updated_by fields
            $validatedData['created_by'] = $request->user()->id; // Assuming the user is authenticated
            $validatedData['updated_by'] = $request->user()->id; // Assuming the user is authenticated
            
            // Create
            $item = WellstackItemModel::create($validatedData);

            return response()->json($item, 201);
        });
    }

    public function updateItem(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            // Validate input
            $validatedData = $request->validate([
                'wellstack_type_id' => 'required|exists:wellstack_types,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|file|image|max:3072',
                'serial_number' => 'nullable|string|max:255',
                'height' => 'nullable|numeric',
                'height_unit' => 'nullable|string|max:10',
                'weight' => 'nullable|numeric',
                'weight_unit' => 'nullable|string|max:10',
                'pressure_rating' => 'nullable|numeric',
                'pressure_rating_unit' => 'nullable|string|max:10',
                'owner' => 'nullable|string|max:255',
                'shear_ram_dist_from_bottom' => 'nullable|numeric',
                'shear_ram_dist_from_bottom_unit' => 'nullable|string|max:10',
            ]);

            // Find the item
            $item = WellstackItemModel::findOrFail($id);

            // Handle file upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();

                $filename = Str::random(40) . '.' . $extension;

                $image->storeAs('public/assets/images/wellstack_items/', $filename);

                // Simpan hanya nama file
                $validatedData['image'] = $filename;
            }

            // Update fields
            $item->fill($validatedData);
            $item->updated_by = $request->user()->id; // Assuming the user is authenticated
            $item->updated_at = now(); // Update the updated_at timestamp
            $item->save();

            return response()->json($item);
        });
    }

    public function getItems(Request $request)
    {
        // Default pagination
        $perPage = $request->input('per_page', 10);

        // Query builder
        $query = WellstackItemModel::with(['updatedByUser']);

        // Filter by type_id
        if ($request->filled('wellstack_type_id')) {
            $query->where('wellstack_type_id', $request->input('wellstack_type_id'));
        }

        // Optional search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Optional status filter (active = not soft-deleted, inactive = soft-deleted)
        if ($request->filled('status')) {
            if ((bool)$request->input('status') === 'active') {
                $query->whereNull('deleted_at');
            } elseif ($request->input('status') === 'inactive') {
                $query->onlyTrashed();
            } elseif ($request->input('status') === 'all') {
                $query->withTrashed();
            }
        }

        // Optional sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $direction = $request->input('direction', 'desc');

        $query->orderBy($sortBy, $direction);

        // Paginate
        $items = $query->paginate($perPage);
        $items->getCollection()->transform(function ($item) {
            $item->image_url = $item->image
                ? Storage::url('assets/images/wellstack_items/' . $item->image)
                : null;
            $item->status = is_null($item->deleted_at) ? 'active' : 'inactive';
            $item->updated_by_name = $item->updatedByUser ? $item->updatedByUser->fullname : null;
            return $item;
        });

        $totalActive = WellstackItemModel::where('wellstack_type_id', $request->input('wellstack_type_id'))->whereNull('deleted_at')->count();
        $totalInactive = WellstackItemModel::where('wellstack_type_id', $request->input('wellstack_type_id'))->onlyTrashed()->count();
        $itemsArray = $items->toArray();
        $itemsArray['total_active_items'] = $totalActive;
        $itemsArray['total_inactive_items'] = $totalInactive;
        return response()->json($itemsArray);
    }

    public function deleteItem(Request $request)
    {
        // Get the IDs from the request
        $ids = $request->input('ids', []);

        // If no IDs are provided, return an error response
        if (empty($ids)) {
            return response()->json(['message' => 'No IDs provided'], 400);
        }

        // Soft delete the items
        WellstackItemModel::whereIn('id', $ids)->delete();

        // Return a success response
        return response()->json(['message' => 'Items deleted successfully'], 204);
    }

    public function getReportingHistories(Request $request)
    {
        // Default pagination
        $perPage = $request->input('per_page', 10);

        // Query builder
        $query = WellstackReportingHistoryModel::with('updatedByUser');

        // Optional search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('client', 'like', "%{$search}%");
            });
        }
        // Optional sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $direction = $request->input('direction', 'desc');
        $query->orderBy($sortBy, $direction);

        // Paginate
        $histories = $query->paginate($perPage);
        $histories->getCollection()->transform(function ($history) {
            $history->updated_by_name = $history->updatedByUser ? $history->updatedByUser->fullname : null;
            if ($history->date) {
                $history->date = Carbon::parse($history->date)->format('Y-m-d');
            }
            return $history;
        });

        return response()->json($histories);
    }

    public function storeReportingHistory(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'client' => 'nullable|string|max:255',
            'field' => 'nullable|string|max:255',
            'well_name_number' => 'nullable|string|max:255',
            'min_restriction' => 'nullable|string|max:255',
            'kop' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'bhp' => 'nullable|string|max:255',
            'bhst' => 'nullable|string|max:255',
            'so' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'date_drawn' => 'nullable|date',
            'drawn_by' => 'nullable|string|max:255',
        ]);

        // Create a new reporting history
        $reportingHistory = WellstackReportingHistoryModel::create([
            'name' => $request->name,
            'client' => $request->client ?? null,
            'field' => $request->field ?? null,
            'well_name_number' => $request->well_name_number ?? null,
            'min_restriction' => $request->min_restriction ?? null,
            'kop' => $request->kop ?? null,
            'category' => $request->category ?? null,
            'bhp' => $request->bhp ?? null,
            'bhst' => $request->bhst ?? null,
            'so' => $request->so ?? null,
            'supplier' => $request->supplier ?? null,
            'date_drawn' => $request->date ? Carbon::parse($request->date) : null,
            'drawn_by' => $request->drawn_by ?? null,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => $request->user()->id, // Assuming the user is authenticated
            'updated_by' => $request->user()->id, // Assuming the user is authenticated
        ]);

        // Return the created reporting history
        return response()->json($reportingHistory, 201);
    }

    public function updateReportingHistory(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'client' => 'nullable|string|max:255',
            'field' => 'nullable|string|max:255',
            'well_name_number' => 'nullable|string|max:255',
            'min_restriction' => 'nullable|string|max:255',
            'kop' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'bhp' => 'nullable|string|max:255',
            'bhst' => 'nullable|string|max:255',
            'so' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'date_drawn' => 'nullable|date',
            'drawn_by' => 'nullable|string|max:255',
        ]);

        // Find the reporting history by ID
        $reportingHistory = WellstackReportingHistoryModel::findOrFail($id);

        // Update the reporting history
        $reportingHistory->name = $request->name;
        $reportingHistory->client = $request->client ?? null;
        $reportingHistory->field = $request->field ?? null;
        $reportingHistory->well_name_number = $request->well_name_number ?? null;
        $reportingHistory->min_restriction = $request->min_restriction ?? null;
        $reportingHistory->kop = $request->kop ?? null;
        $reportingHistory->category = $request->category ?? null;
        $reportingHistory->bhp = $request->bhp ?? null;
        $reportingHistory->bhst = $request->bhst ?? null;
        $reportingHistory->so = $request->so ?? null;
        $reportingHistory->supplier = $request->supplier ?? null;
        $reportingHistory->date_drawn = $request->date_drawn ? Carbon::parse($request->date_drawn) : null;
        $reportingHistory->drawn_by = $request->drawn_by ?? null;
        $reportingHistory->updated_at = now();
        $reportingHistory->updated_by = $request->user()->id; // Assuming the user is authenticated
        $reportingHistory->save();

        // Return the updated reporting history
        return response()->json($reportingHistory);
    }
}
