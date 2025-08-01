<?php

namespace App\Http\Controllers;

use App\Models\ThreadModel;
use App\Models\ToolstringTypeModel;
use App\Models\ToolstringItemDimensionModel;
use App\Models\ToolstringItemModel;
use App\Models\ToolstringReportingHistoryDetailModel;
use App\Models\ToolstringReportingHistoryModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Mpdf\Mpdf;

class ToolstringController extends Controller
{
    public function storeType(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $slug = str($request->name)
            ->slug()
            ->lower();

        // Create a new type
        $type = ToolstringTypeModel::create([
            'name' => $request->name,
            'slug' => $slug,
            'created_by' => $request->user()->id, // Assuming the user is authenticated
            'updated_by' => $request->user()->id, // Assuming the user is authenticated
        ]);

        // Return the created type
        return response()->json($type, 201);
    }

    public function getTypes()
    {
        // Retrieve all types
        $types = ToolstringTypeModel::orderBy('name', 'asc')->get();

        // Return the types
        return response()->json($types);
    }

    public function searchTypes(Request $request)
    {
        // Retrieve all types with search functionality
        $search = $request->input('search', '');
        $types = ToolstringTypeModel::where('name', 'like', "%{$search}%")
            ->orderBy('name', 'asc')
            ->get();

        // Return the types
        return response()->json($types);
    }

    public function getType($id)
    {
        // Find the type by ID
        $type = ToolstringTypeModel::findOrFail($id);

        // Return the type
        return response()->json($type);
    }

    public function updateType(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Find the type by ID
        $type = ToolstringTypeModel::findOrFail($id);

        // Update the type
        $type->name = $request->name;
        $type->slug = str($request->name)->slug()->lower();
        $type->updated_by = $request->user()->id; // Assuming the user is authenticated
        $type->save();

        // Return the updated type
        return response()->json($type);
    }

    public function deleteType($id)
    {
        // Find the type by ID
        $type = ToolstringTypeModel::findOrFail($id);

        // Delete the type
        $type->delete();

        // Return a success response
        return response()->json(['message' => 'Type deleted successfully'], 204);
    }

    public function getItems(Request $request)
    {
        // Default pagination
        $perPage = $request->input('per_page', 10);

        // Query builder
        $query = ToolstringItemModel::with(['toolstringType', 'updatedByUser', 'thread', 'threadSize']);

        // Filter by type_id
        if ($request->filled('toolstring_type_id')) {
            $query->where('toolstring_type_id', $request->input('toolstring_type_id'));
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
        $status = $request->input('status');
        if ($status === 'active') {
            $query->whereNull('deleted_at');
        } elseif ($status === 'inactive') {
            $query->onlyTrashed();
        } elseif ($status === 'all') {
            $query->withTrashed();
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
            $item->thread = $item->thread ? [
                'id' => $item->thread->id,
                'type' => $item->thread->type,
            ] : null;
            $item->thread_size = $item->threadSize ? [
                'id' => $item->threadSize->id,
                'top_connection' => $item->threadSize->top_connection,
                'bottom_connection' => $item->threadSize->bottom_connection,
            ] : null;
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

        $totalActive = ToolstringItemModel::where('toolstring_type_id', $request->input('toolstring_type_id'))->whereNull('deleted_at')->count();
        $totalInactive = ToolstringItemModel::where('toolstring_type_id', $request->input('toolstring_type_id'))->onlyTrashed()->count();
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
                'toolstring_type_id' => 'required|exists:toolstring_types,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|file|image|max:3072',
                'dimension_sets' => 'nullable|json', // Assuming dimensions are sent as JSON
                'thread_id' => 'nullable|exists:threads,id',
                'thread_size_id' => 'nullable|exists:thread_sizes,id',
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
            $validatedData['thread_id'] = $validatedData['thread_id'] ?? null;
            $validatedData['thread_size_id'] = $validatedData['thread_size_id'] ?? null;

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
                'toolstring_type_id' => 'required|exists:toolstring_types,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|file|image|max:3072',
                'dimension_sets' => 'nullable|json', // Assuming dimensions are sent as JSON
                'dimension_sets_deleted_ids' => 'nullable|json', // IDs of dimensions to delete
                'thread_id' => 'nullable|exists:threads,id',
                'thread_size_id' => 'nullable|exists:thread_sizes,id',
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
            $item->updated_at = now(); // Update the updated_at timestamp
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

    public function searchItemByIdType(Request $request)
    {
        // Validate the request
        $request->validate([
            'toolstring_type_id' => 'required',
            'search' => 'nullable|string|max:255',
        ]);

        // Retrieve items by type with optional search
        $query = ToolstringItemModel::where('toolstring_type_id', $request->input('toolstring_type_id'));

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
        $query->where('created_by', $request->user()->id); // Assuming the user is authenticated
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

    public function deleteReportingHistory(Request $request)
    {
        // Get the IDs from the request
        $ids = $request->input('ids', []);

        // If no IDs are provided, return an error response
        if (empty($ids)) {
            return response()->json(['message' => 'No IDs provided'], 400);
        }

        // Soft delete the reporting histories
        $deletedCount = ToolstringReportingHistoryModel::whereIn('id', $ids)->delete();

        if ($deletedCount > 0) {
            return response()->json(['message' => 'Reporting histories deleted successfully', 'deleted_count' => $deletedCount], 204);
        } else {
            return response()->json(['message' => 'No reporting histories found for the provided IDs'], 404);
        }
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
            'toolstring_type_id' => 'required|exists:toolstring_types,id',
            'toolstring_item_id' => 'required|exists:toolstring_items,id',
            'toolstring_item_dimension_id' => 'required|exists:toolstring_item_dimensions,id',
            'position' => 'nullable|integer',
        ]);

        // Create a new reporting history detail
        $reportingHistoryDetail = ToolstringReportingHistoryDetailModel::create([
            'toolstring_reporting_history_id' => $request->toolstring_reporting_history_id,
            'toolstring_type_id' => $request->toolstring_type_id,
            'toolstring_item_id' => $request->toolstring_item_id,
            'toolstring_item_dimension_id' => $request->toolstring_item_dimension_id,
            'position' => $request->position,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => $request->user()->id, // Assuming the user is authenticated
            'updated_by' => $request->user()->id, // Assuming the user is authenticated
        ]);

        // Return the created reporting history detail
        return response()->json($reportingHistoryDetail, 201);
    }

    public function getReportingHistoryDetails($templateId)
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
            ->where('updated_by', Auth::id()) // Assuming the user is authenticated
            ->with(['reportingHistory', 'item', 'dimension'])
            ->orderBy('position', 'asc')
            ->get();

        // Transform the details to include additional information
        $details = $details->map(function ($detail) {
            return [
                'id' => $detail->id,
                'position' => $detail->position,
                'item_name' => optional($detail->item)->name,
                'description' => optional($detail->item)->description,
                'type_name' => optional($detail->type)->name,
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
                'thread' => ThreadModel::find(optional($detail->item)->thread_id) ? [
                    'id' => optional($detail->item)->thread_id,
                    'type' => optional($detail->item)->thread->type ?? null,
                ] : null,
                'thread_size' => optional($detail->item)->threadSize ? [
                    'id' => optional($detail->item)->threadSize->id,
                    'top_connection' => optional($detail->item)->threadSize->top_connection,
                    'bottom_connection' => optional($detail->item)->threadSize->bottom_connection,
                ] : null,
            ];
        });

        // Return the details
        return response()->json($details);
    }

    public function deleteReportingHistoryDetail(Request $request)
    {
        // Get the IDs from the request
        $ids = $request->input('ids', []);

        // If no IDs are provided, return an error response
        if (empty($ids)) {
            return response()->json(['message' => 'No IDs provided'], 400);
        }

        // Delete the reporting history details
        ToolstringReportingHistoryDetailModel::whereIn('id', $ids)->delete();

        // Return a success response
        return response()->json(['message' => 'Reporting history details deleted successfully'], 204);
    }

    public function updateReportingHistoryDetailPosition(Request $request)
    {
        // Validate the request
        $request->validate([
            'components' => 'required|array',
            'components.*.id' => 'required|exists:toolstring_reporting_history_details,id',
            'components.*.position' => 'required|integer',
        ]);

        // Update positions
        foreach ($request->components as $component) {
            ToolstringReportingHistoryDetailModel::where('id', $component['id'])
                ->update(['position' => $component['position']]);
        }

        // Return a success response
        return response()->json(['message' => 'Positions updated successfully'], 200);
    }

    public function exportReportingHistoryPdf(Request $request, $templateId)
    {
        $get_all_components = ToolstringReportingHistoryDetailModel::where('toolstring_reporting_history_id', $templateId)
            ->with(['reportingHistory', 'item', 'dimension'])
            ->orderBy('position', 'asc')
            ->get();

        $selected_od_unit_convertion = $request->query('od_unit', 'inch');
        $selected_id_unit_convertion = $request->query('id_unit', 'inch');
        $selected_length_unit_convertion = $request->query('length_unit', 'inch');

        $get_all_components = $get_all_components->map(function ($detail) use ($selected_od_unit_convertion, $selected_id_unit_convertion, $selected_length_unit_convertion) {
            return [
                'id' => $detail->id,
                'position' => $detail->position,
                'item_name' => optional($detail->item)->name,
                'description' => optional($detail->item)->description,
                'type_name' => optional($detail->type)->name,
                'image_url' => $detail->item && $detail->item->image
                    ? Storage::url('assets/images/toolstring_items/' . $detail->item->image)
                    : null,
                // 'image_base64' => $detail->item && $detail->item->image
                //     ? $this->getImageAsBase64('assets/images/toolstring_items/' . $detail->item->image)
                //     : null,
                'dimension' => [
                    'outer_diameter' => [
                        'value' => $this->convertDimensionValue(
                            optional($detail->dimension)->outer_diameter,
                            optional($detail->dimension)->outer_diameter_unit,
                            $selected_od_unit_convertion
                        ),
                        'unit' => $selected_od_unit_convertion,
                    ],
                    'inner_diameter' => [
                        'value' => $this->convertDimensionValue(
                            optional($detail->dimension)->inner_diameter,
                            optional($detail->dimension)->inner_diameter_unit,
                            $selected_id_unit_convertion
                        ),
                        'unit' => $selected_id_unit_convertion,
                    ],
                    'length' => [
                        'value' => $this->convertDimensionValue(
                            optional($detail->dimension)->length,
                            optional($detail->dimension)->length_unit,
                            $selected_length_unit_convertion
                        ),
                        'unit' => $selected_length_unit_convertion,
                    ],
                ],
                'thread' => ThreadModel::find(optional($detail->item)->thread_id) ? [
                    'id' => optional($detail->item)->thread_id,
                    'type' => optional($detail->item)->thread->type ?? null,
                ] : null,
                'thread_size' => optional($detail->item)->threadSize ? [
                    'id' => optional($detail->item)->threadSize->id,
                    'top_connection' => optional($detail->item)->threadSize->top_connection,
                    'bottom_connection' => optional($detail->item)->threadSize->bottom_connection,
                ] : null,
            ];
        });

        $heightPDF = $request->query('height_pdf', 1500);
        $reportingHistory = ToolstringReportingHistoryModel::findOrFail($templateId);
        $formattedDate = \Carbon\Carbon::parse($reportingHistory->date)->format('j/n/Y');
        $logoBase64 = $this->imageToBase64FromPublic('assets/images/company/company-logo.png');
        $data = [
            'components' => $get_all_components,
            'reportingHistory' => ToolstringReportingHistoryModel::findOrFail($templateId),
            'formattedDate' => $formattedDate,
            'odUnit' => $selected_od_unit_convertion,
            'idUnit' => $selected_id_unit_convertion,
            'lengthUnit' => $selected_length_unit_convertion,
            'company_logo' => $logoBase64,
        ];
        $html = view('pdf.toolstring-reporting', $data)->render();
        $mpdfConfig = [
            'mode' => 'utf-8',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_header' => 5,
            'margin_footer' => 5,
            'orientation' => 'P',
            'default_font_size' => 10,
            'default_font' => 'sans-serif',
            'format' => [210, $heightPDF],
            'tempDir' => storage_path('app/temp'), // Laravel
            // atau 'tempDir' => public_path('temp'), // untuk direktori public
            'simpleTables' => false,
        ];

        // Inisialisasi mPDF
        $mpdf = new Mpdf($mpdfConfig);
        $mpdf->SetAutoPageBreak(false);

        // Tambahkan konten
        if (strlen($html) > 500000) { // 500KB
            $chunks = str_split($html, 200000); // 200KB per chunk
            foreach ($chunks as $index => $chunk) {
                if ($index > 0) $mpdf->AddPage();
                $mpdf->WriteHTML($chunk);
            }
        } else {
            $mpdf->WriteHTML($html);
        }

        // Output PDF
        $timestamp = time();
        $filename = "Toolstring_Reporting_{$reportingHistory->name}_{$timestamp}.pdf";
        return $mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
    }

    /**
     * Convert dimension value from one unit to another
     */
    private function convertDimensionValue($value, $fromUnit, $toUnit)
    {
        // Return original value if no conversion needed or value is null
        if (!$value || !$fromUnit || !$toUnit || $fromUnit === $toUnit) {
            return floatval($value);
        }

        // Convert to mm as base unit first
        $valueInMm = 0;
        switch (strtolower($fromUnit)) {
            case 'inch':
                $valueInMm = $value * 25.4;
                break;
            case 'cm':
                $valueInMm = $value * 10;
                break;
            case 'mm':
                $valueInMm = $value;
                break;
            default:
                return floatval($value); // Return original if unknown unit
        }

        // Convert from mm to target unit
        switch (strtolower($toUnit)) {
            case 'inch':
                return round($valueInMm / 25.4, 2);
            case 'cm':
                return round($valueInMm / 10, 2);
            case 'mm':
                return round($valueInMm, 2);
            default:
                return floatval($value); // Return original if unknown unit
        }
    }

    private function getImageAsBase64($path)
    {
        $fullPath = Storage::disk('public')->path($path);

        if (!file_exists($fullPath)) {
            return null;
        }

        $type = pathinfo($fullPath, PATHINFO_EXTENSION);
        $data = file_get_contents($fullPath);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return $base64;
    }

    private function imageToBase64FromPublic($relativePath)
    {
        $fullPath = public_path($relativePath);

        if (!file_exists($fullPath)) {
            return null;
        }

        $type = pathinfo($fullPath, PATHINFO_EXTENSION);
        $data = file_get_contents($fullPath);

        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    public function restoreItem(Request $request)
    {
        // Get the IDs from the request
        $ids = $request->input('ids', []);

        // If no IDs are provided, return an error response
        if (empty($ids)) {
            return response()->json(['message' => 'No IDs provided'], 400);
        }

        // Restore the items
        ToolstringItemModel::withTrashed()->whereIn('id', $ids)->restore();

        // Return a success response
        return response()->json(['message' => 'Items restored successfully'], 200);
    }
}
