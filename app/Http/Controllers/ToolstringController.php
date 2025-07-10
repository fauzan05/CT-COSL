<?php

namespace App\Http\Controllers;

use App\Models\ToolstringCategoryModel;
use App\Models\ToolstringItemDimensionModel;
use App\Models\ToolstringItemModel;
use App\Models\ToolstringReportingHistoryDetailModel;
use App\Models\ToolstringReportingHistoryModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            'created_by' => $request->user()->id, // Assuming the user is authenticated
            'updated_by' => $request->user()->id, // Assuming the user is authenticated
        ]);

        // Return the created category
        return response()->json($category, 201);
    }

    public function getCategories()
    {
        // Retrieve all categories
        $categories = ToolstringCategoryModel::orderBy('name', 'asc')->get();

        // Return the categories
        return response()->json($categories);
    }

    public function searchCategories(Request $request)
    {
        // Retrieve all categories with search functionality
        $search = $request->input('search', '');
        $categories = ToolstringCategoryModel::where('name', 'like', "%{$search}%")
            ->orderBy('name', 'asc')
            ->get();

        // Return the categories
        return response()->json($categories);
    }

    public function getCategory($id)
    {
        // Find the category by ID
        $category = ToolstringCategoryModel::findOrFail($id);

        // Return the category
        return response()->json($category);
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
        $category->updated_by = $request->user()->id; // Assuming the user is authenticated
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

    public function getItems(Request $request)
    {
        // Default pagination
        $perPage = $request->input('per_page', 10);

        // Query builder
        $query = ToolstringItemModel::with(['toolstringCategory', 'updatedByUser']);

        // Filter by category_id
        if ($request->filled('toolstring_category_id')) {
            $query->where('toolstring_category_id', $request->input('toolstring_category_id'));
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
                ? Storage::url('assets/images/toolstring_items/' . $item->image)
                : null;
            $item->status = is_null($item->deleted_at) ? 'active' : 'inactive';
            $item->updated_by_name = $item->updatedByUser ? $item->updatedByUser->fullname : null;
            $item->dimension_sets = ToolstringItemDimensionModel::where('toolstring_item_id', $item->id)
                ->get()
                ->map(function ($dimension) {
                    return [
                        'id' => $dimension->id,
                        'outer_diameter' => [
                            'value' => $dimension->outer_diameter,
                            'unit' => $dimension->outer_diameter_unit,
                        ],
                        'inner_diameter' => [
                            'value' => $dimension->inner_diameter,
                            'unit' => $dimension->inner_diameter_unit,
                        ],
                        'length' => [
                            'value' => $dimension->length,
                            'unit' => $dimension->length_unit,
                        ],
                        'is_current' => true, // Assuming all dimensions are current
                    ];
                });
            return $item;
        });

        $totalActive = ToolstringItemModel::where('toolstring_category_id', $request->input('toolstring_category_id'))->whereNull('deleted_at')->count();
        $totalInactive = ToolstringItemModel::where('toolstring_category_id', $request->input('toolstring_category_id'))->onlyTrashed()->count();
        $itemsArray = $items->toArray();
        $itemsArray['total_active_items'] = $totalActive;
        $itemsArray['total_inactive_items'] = $totalInactive;
        return response()->json($itemsArray);
    }

    public function storeItem(Request $request)
    {
        DB::transaction(function () use ($request) {
            // Validate input
            $validatedData = $request->validate([
                'toolstring_category_id' => 'required|exists:toolstring_categories,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|file|image|max:3072',
                'dimension_sets' => 'nullable|json', // Assuming dimensions are sent as JSON
            ]);

            // Handle file upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();

                $filename = Str::random(40) . '.' . $extension;

                $image->storeAs('public/assets/images/toolstring_items/', $filename);

                // Simpan hanya nama file
                $validatedData['image'] = $filename;
            }

            // Set created_by and updated_by fields
            $validatedData['created_by'] = $request->user()->id; // Assuming the user is authenticated
            $validatedData['updated_by'] = $request->user()->id; // Assuming the user is authenticated

            // Create
            $item = ToolstringItemModel::create($validatedData);

            // Handle dimensions if provided
            if ($request->filled('dimension_sets')) {
                $dimensionSets = json_decode($request->dimension_sets);
                $dimensionsData = [];

                foreach ($dimensionSets as $dimension) {
                    $dimensionsData[] = [
                        'toolstring_item_id'    => $item->id,
                        'outer_diameter'        => $dimension->outer_diameter->value ?? null,
                        'outer_diameter_unit'   => $dimension->outer_diameter->unit ?? null,
                        'inner_diameter'        => $dimension->inner_diameter->value ?? null,
                        'inner_diameter_unit'   => $dimension->inner_diameter->unit ?? null,
                        'length'                => $dimension->length->value ?? null,
                        'length_unit'           => $dimension->length->unit ?? null,
                        'created_at'            => now(),
                        'created_by'            => $request->user()->id, // Assuming the user is authenticated
                        'updated_at'            => now(),
                        'updated_by'            => $request->user()->id, // Assuming the user is authenticated
                    ];
                }

                ToolstringItemDimensionModel::insert($dimensionsData);
            }

            return response()->json($item, 201);
        });
    }

    public function updateItem(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            // Validate input
            $validatedData = $request->validate([
                'toolstring_category_id' => 'required|exists:toolstring_categories,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|file|image|max:3072',
                'dimension_sets' => 'nullable|json', // Assuming dimensions are sent as JSON
                'dimension_sets_deleted_ids' => 'nullable|json', // IDs of dimensions to delete
            ]);

            // Find the item
            $item = ToolstringItemModel::findOrFail($id);

            // Handle file upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();

                $filename = Str::random(40) . '.' . $extension;

                $image->storeAs('public/assets/images/toolstring_items/', $filename);

                // Simpan hanya nama file
                $validatedData['image'] = $filename;
            }

            if ($request->filled('dimension_sets')) {
                $dimensionSets = json_decode($request->dimension_sets);
                foreach ($dimensionSets as $dimension) {
                    if (!$dimension->is_current) {
                        ToolstringItemDimensionModel::create([
                            'toolstring_item_id'    => $item->id,
                            'outer_diameter'        => $dimension->outer_diameter->value ?? null,
                            'outer_diameter_unit'   => $dimension->outer_diameter->unit ?? null,
                            'inner_diameter'        => $dimension->inner_diameter->value ?? null,
                            'inner_diameter_unit'   => $dimension->inner_diameter->unit ?? null,
                            'length'                => $dimension->length->value ?? null,
                            'length_unit'           => $dimension->length->unit ?? null,
                            'created_at'            => now(),
                            'created_by'            => $request->user()->id, // Assuming the user is authenticated
                            'updated_at'            => now(),
                            'updated_by'            => $request->user()->id, // Assuming the user is authenticated
                        ]);
                    } else {
                        // update dimension
                        $toolstringItemDimension = ToolstringItemDimensionModel::find($dimension->id);
                        if ($toolstringItemDimension) {
                            $toolstringItemDimension->outer_diameter = $dimension->outer_diameter->value ?? null;
                            $toolstringItemDimension->outer_diameter_unit = $dimension->outer_diameter->unit ?? null;
                            $toolstringItemDimension->inner_diameter = $dimension->inner_diameter->value ?? null;
                            $toolstringItemDimension->inner_diameter_unit = $dimension->inner_diameter->unit ?? null;
                            $toolstringItemDimension->length = $dimension->length->value ?? null;
                            $toolstringItemDimension->length_unit = $dimension->length->unit ?? null;
                            $toolstringItemDimension->updated_at = now();
                            $toolstringItemDimension->updated_by = $request->user()->id; // Assuming the user is authenticated
                            $toolstringItemDimension->save();
                        }
                    }
                }
            }

            // Handle deletion of dimensions
            if ($request->filled('dimension_sets_deleted_ids')) {
                $deletedIds = json_decode($request->dimension_sets_deleted_ids);
                ToolstringItemDimensionModel::whereIn('id', $deletedIds)->delete();
            }

            // Update fields
            $item->fill($validatedData);
            $item->updated_by = $request->user()->id; // Assuming the user is authenticated
            $item->save();

            return response()->json($item);
        });
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
        ToolstringItemModel::whereIn('id', $ids)->delete();

        // Return a success response
        return response()->json(['message' => 'Items deleted successfully'], 204);
    }

    public function searchItemByIdCategory(Request $request)
    {
        // Validate the request
        $request->validate([
            'toolstring_category_id' => 'required',
            'search' => 'nullable|string|max:255',
        ]);

        // Retrieve items by category with optional search
        $query = ToolstringItemModel::where('toolstring_category_id', $request->input('toolstring_category_id'));

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Get the items
        $items = $query->get();
        $items->transform(function ($item) {
            $item->image_url = $item->image
                ? Storage::url('assets/images/toolstring_items/' . $item->image)
                : null;
            return $item;
        });

        // Return the items
        return response()->json($items);
    }

    public function storeReportingHistory(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'well' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        // Create a new reporting history
        $reportingHistory = ToolstringReportingHistoryModel::create([
            'name' => $request->name,
            'title' => $request->title,
            'client' => $request->client,
            'well' => $request->well,
            'date' => $request->date,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => $request->user()->id, // Assuming the user is authenticated
            'updated_by' => $request->user()->id, // Assuming the user is authenticated
        ]);

        // Return the created reporting history
        return response()->json($reportingHistory, 201);
    }

    public function getReportingHistories(Request $request)
    {
        // Default pagination
        $perPage = $request->input('per_page', 10);

        // Query builder
        $query = ToolstringReportingHistoryModel::with('updatedByUser');

        // Optional search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('client', 'like', "%{$search}%")
                    ->orWhere('well', 'like', "%{$search}%");
            });
        }
        // Optional sorting
        $sortBy = $request->input('sort_by', 'date');
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

    public function getReportingHistory($id)
    {
        // Find the reporting history by ID
        $reportingHistory = ToolstringReportingHistoryModel::with('updatedByUser')->findOrFail($id);

        // Format the date
        if ($reportingHistory->date) {
            $reportingHistory->date = Carbon::parse($reportingHistory->date)->format('Y-m-d');
        }

        // Return the reporting history
        return response()->json($reportingHistory);
    }

    public function updateReportingHistory(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'well' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        // Find the reporting history by ID
        $reportingHistory = ToolstringReportingHistoryModel::findOrFail($id);

        // Update the reporting history
        $reportingHistory->name = $request->name;
        $reportingHistory->title = $request->title;
        $reportingHistory->client = $request->client;
        $reportingHistory->well = $request->well;
        $reportingHistory->date = Carbon::parse($request->date)->format('Y-m-d');
        $reportingHistory->updated_by = $request->user()->id; // Assuming the user is authenticated
        $reportingHistory->save();

        // Return the updated reporting history
        return response()->json($reportingHistory);
    }

    public function getItemDimensions($itemId)
    {
        // Find the item by ID
        $item = ToolstringItemModel::findOrFail($itemId);

        // Get the dimensions for the item
        $dimensions = ToolstringItemDimensionModel::where('toolstring_item_id', $item->id)->get();

        // Transform dimensions to include units
        $dimensionsArray = $dimensions->map(function ($dimension) {
            return [
                'id' => $dimension->id,
                'outer_diameter' => [
                    'value' => $dimension->outer_diameter,
                    'unit' => $dimension->outer_diameter_unit,
                ],
                'inner_diameter' => [
                    'value' => $dimension->inner_diameter,
                    'unit' => $dimension->inner_diameter_unit,
                ],
                'length' => [
                    'value' => $dimension->length,
                    'unit' => $dimension->length_unit,
                ],
            ];
        });

        // Return the dimensions
        return response()->json($dimensionsArray);
    }

    public function storeReportingHistoryDetail(Request $request)
    {
        // Validate the request data
        $request->validate([
            'toolstring_reporting_history_id' => 'required|exists:toolstring_reporting_histories,id',
            'toolstring_category_id' => 'required|exists:toolstring_categories,id',
            'toolstring_item_id' => 'required|exists:toolstring_items,id',
            'toolstring_item_dimension_id' => 'required|exists:toolstring_item_dimensions,id',
        ]);

        // Create a new reporting history detail
        $reportingHistoryDetail = ToolstringReportingHistoryDetailModel::create([
            'toolstring_reporting_history_id' => $request->toolstring_reporting_history_id,
            'toolstring_category_id' => $request->toolstring_category_id,
            'toolstring_item_id' => $request->toolstring_item_id,
            'toolstring_item_dimension_id' => $request->toolstring_item_dimension_id,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => $request->user()->id, // Assuming the user is authenticated
            'updated_by' => $request->user()->id, // Assuming the user is authenticated
        ]);

        // Return the created reporting history detail
        return response()->json($reportingHistoryDetail, 201);
    }

    public function getReportingHistoryDetails(Request $request, $templateId)
    {
        // Validate the request
        $validator = Validator::make(
            ['templateId' => $templateId],
            ['templateId' => 'required|exists:toolstring_reporting_histories,id']
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid template ID',
                'errors' => $validator->errors()
            ], 422);
        }

        // Retrieve reporting history details by template ID
        $details = ToolstringReportingHistoryDetailModel::where('toolstring_reporting_history_id', $templateId)
            ->with(['reportingHistory', 'item', 'dimension'])
            ->get();

        // Transform the details to include additional information
        $details = $details->map(function ($detail) {
            return [
                'item_name' => optional($detail->item)->name,
                'category_name' => optional($detail->category)->name,
                'image_url' => $detail->item && $detail->item->image
                    ? Storage::url('assets/images/toolstring_items/' . $detail->item->image)
                    : null,
                'dimension' => [
                    'outer_diameter' => [
                        'value' => optional($detail->dimension)->outer_diameter,
                        'unit' => optional($detail->dimension)->outer_diameter_unit,
                    ],
                    'inner_diameter' => [
                        'value' => optional($detail->dimension)->inner_diameter,
                        'unit' => optional($detail->dimension)->inner_diameter_unit,
                    ],
                    'length' => [
                        'value' => optional($detail->dimension)->length,
                        'unit' => optional($detail->dimension)->length_unit,
                    ],
                ],
            ];
        });

        // Return the details
        return response()->json($details);
    }
}
