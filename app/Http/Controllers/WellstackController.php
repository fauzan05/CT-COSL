<?php

namespace App\Http\Controllers;

use App\Models\WellstackItemModel;
use App\Models\WellstackReportingHistoryDetailModel;
use App\Models\WellstackReportingHistoryModel;
use App\Models\WellstackTypeModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Mpdf\Mpdf;

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

    public function searchTypes(Request $request)
    {
        $search = $request->input('search', '');
        $types = WellstackTypeModel::where('name', 'like', "%{$search}%")
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($types);
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
        $wellstackTypeId = $request->input('wellstack_type_id');

        // Base query with eager loading
        $query = WellstackItemModel::with(['updatedByUser:id,fullname'])
            ->select([
                'id',
                'name',
                'description',
                'image',
                'wellstack_type_id',
                'serial_number',
                'height',
                'height_unit',
                'weight',
                'weight_unit',
                'pressure_rating',
                'pressure_rating_unit',
                'owner',
                'shear_ram_dist_from_bottom',
                'shear_ram_dist_from_bottom_unit',
                'updated_by',
                'created_at',
                'updated_at',
                'deleted_at'
            ]);

        // Filter by type_id (apply early for better performance)
        if ($wellstackTypeId) {
            $query->where('wellstack_type_id', $wellstackTypeId);
        }

        // Search optimization with index hints
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter optimization
        if ($request->filled('status')) {
            $status = $request->input('status');
            switch ($status) {
                case 'active':
                    $query->whereNull('deleted_at');
                    break;
                case 'inactive':
                    $query->onlyTrashed();
                    break;
                case 'all':
                    $query->withTrashed();
                    break;
            }
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $direction = $request->input('direction', 'desc');
        $allowedSortColumns = ['id', 'name', 'created_at', 'updated_at'];

        if (in_array($sortBy, $allowedSortColumns)) {
            $query->orderBy($sortBy, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // For counting, we need to get filtered data only once
        $countQuery = WellstackItemModel::select(['deleted_at']);

        // Apply same filters as main query for accurate counting
        if ($wellstackTypeId) {
            $countQuery->where('wellstack_type_id', $wellstackTypeId);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $countQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Get all filtered items for counting (only deleted_at column)
        $allFilteredItems = $countQuery->withTrashed()->get(['deleted_at']);

        // Count in PHP - much faster than database aggregation
        $totalActive = $allFilteredItems->whereNull('deleted_at')->count();
        $totalInactive = $allFilteredItems->whereNotNull('deleted_at')->count();

        // Now apply status filter to main query if needed
        if ($request->filled('status')) {
            $status = $request->input('status');
            switch ($status) {
                case 'active':
                    $query->whereNull('deleted_at');
                    break;
                case 'inactive':
                    $query->onlyTrashed();
                    break;
                case 'all':
                    $query->withTrashed();
                    break;
            }
        }

        // Get paginated results
        $items = $query->paginate($perPage);

        // Transform collection efficiently
        $items->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'image_url' => $item->image
                    ? Storage::url('assets/images/wellstack_items/' . $item->image)
                    : null,
                'wellstack_type_id' => $item->wellstack_type_id,
                'serial_number' => $item->serial_number,
                'height' => $item->height,
                'height_unit' => $item->height_unit,
                'weight' => $item->weight,
                'weight_unit' => $item->weight_unit,
                'pressure_rating' => $item->pressure_rating,
                'pressure_rating_unit' => $item->pressure_rating_unit,
                'owner' => $item->owner,
                'shear_ram_dist_from_bottom' => $item->shear_ram_dist_from_bottom,
                'shear_ram_dist_from_bottom_unit' => $item->shear_ram_dist_from_bottom_unit,
                'created_by_name' => $item->createdByUser?->fullname,
                'status' => is_null($item->deleted_at) ? 'active' : 'inactive',
                'updated_by_name' => $item->updatedByUser?->fullname,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        });

        // Build response
        $response = $items->toArray();
        $response['total_active_items'] = $totalActive;
        $response['total_inactive_items'] = $totalInactive;

        return response()->json($response);
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

    public function deleteReportingHistory(Request $request)
    {
        // Get the IDs from the request
        $ids = $request->input('ids', []);

        // If no IDs are provided, return an error response
        if (empty($ids)) {
            return response()->json(['message' => 'No IDs provided'], 400);
        }

        // Delete the reporting histories
        $deletedCount = WellstackReportingHistoryModel::whereIn('id', $ids)->delete();

        if ($deletedCount > 0) {
            return response()->json(['message' => 'Reporting histories deleted successfully', 'deleted_count' => $deletedCount], 200);
        } else {
            return response()->json(['message' => 'No reporting histories found for the provided IDs'], 404);
        }
    }

    public function getReportingHistoryDetails($templateId)
    {
        // Validate the request
        $validator = Validator::make(
            ['templateId' => $templateId],
            ['templateId' => 'required|exists:wellstack_reporting_histories,id']
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid template ID',
                'errors' => $validator->errors()
            ], 422);
        }

        // Retrieve reporting history details by template ID
        $details = WellstackReportingHistoryDetailModel::where('wellstack_reporting_history_id', $templateId)
            ->with(['reportingHistory', 'item'])
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
                    ? Storage::url('assets/images/wellstack_items/' . $detail->item->image)
                    : null,
                'serial_number' => optional($detail->item)->serial_number,
                'height' => optional($detail->item)->height,
                'height_unit' => optional($detail->item)->height_unit,
                'weight' => optional($detail->item)->weight,
                'weight_unit' => optional($detail->item)->weight_unit,
                'pressure_rating' => optional($detail->item)->pressure_rating,
                'pressure_rating_unit' => optional($detail->item)->pressure_rating_unit,
                'owner' => optional($detail->item)->owner,
                'shear_ram_dist_from_bottom' => optional($detail->item)->shear_ram_dist_from_bottom,
                'shear_ram_dist_from_bottom_unit' => optional($detail->item)->shear_ram_dist_from_bottom_unit,
            ];
        });

        // Return the details
        return response()->json($details);
    }

    public function searchItemByIdType(Request $request)
    {
        // Validate the request
        $request->validate([
            'wellstack_type_id' => 'required',
            'search' => 'nullable|string|max:255',
        ]);

        // Retrieve items by type with optional search
        $query = WellstackItemModel::where('wellstack_type_id', $request->input('wellstack_type_id'));

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
                ? Storage::url('assets/images/wellstack_items/' . $item->image)
                : null;
            return $item;
        });

        // Return the items
        return response()->json($items);
    }

    public function storeReportingHistoryDetail(Request $request)
    {
        // Validate the request data
        $request->validate([
            'wellstack_reporting_history_id' => 'required|exists:wellstack_reporting_histories,id',
            'wellstack_type_id' => 'required|exists:wellstack_types,id',
            'wellstack_item_id' => 'required|exists:wellstack_items,id',
            'position' => 'nullable|integer',
        ]);

        // Create a new reporting history detail
        $reportingHistoryDetail = WellstackReportingHistoryDetailModel::create([
            'wellstack_reporting_history_id' => $request->wellstack_reporting_history_id,
            'wellstack_type_id' => $request->wellstack_type_id,
            'wellstack_item_id' => $request->wellstack_item_id,
            'position' => $request->position,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => $request->user()->id, // Assuming the user is authenticated
            'updated_by' => $request->user()->id, // Assuming the user is authenticated
        ]);

        // Return the created reporting history detail
        return response()->json($reportingHistoryDetail, 201);
    }

    public function updateReportingHistoryDetailPosition(Request $request)
    {
        // Validate the request
        $request->validate([
            'components' => 'required|array',
            'components.*.id' => 'required|exists:wellstack_reporting_history_details,id',
            'components.*.position' => 'required|integer',
        ]);

        // Update positions
        foreach ($request->components as $component) {
            WellstackReportingHistoryDetailModel::where('id', $component['id'])
                ->update(['position' => $component['position']]);
        }

        // Return a success response
        return response()->json(['message' => 'Positions updated successfully'], 200);
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
        WellstackReportingHistoryDetailModel::whereIn('id', $ids)->delete();

        // Return a success response
        return response()->json(['message' => 'Reporting history details deleted successfully'], 204);
    }

    public function exportReportingHistoryPdf(Request $request, $templateId)
    {
        $get_all_components = WellstackReportingHistoryDetailModel::where('wellstack_reporting_history_id', $templateId)
            ->with(['item', 'type'])
            ->orderBy('position', 'asc')
            ->get();

        $distance_from_lower_shear = 0;
        $distance_from_upper_shear = 0;

        $get_all_components = $get_all_components->map(function ($component) {
            return [
                'id' => $component->id,
                'position' => $component->position,
                'item_name' => optional($component->item)->name,
                'description' => optional($component->item)->description,
                'type_name' => optional($component->type)->name,
                'image_url' => $component->item && $component->item->image
                    ? Storage::url('assets/images/wellstack_items/' . $component->item->image)
                    : null,
                'image_base64' => $component->item && $component->item->image
                    ? $this->getImageAsBase64('assets/images/wellstack_items/' . $component->item->image)
                    : null,
                'serial_number' => optional($component->item)->serial_number,
                'height' => optional($component->item)->height,
                'height_unit' => optional($component->item)->height_unit,
                'weight' => optional($component->item)->weight,
                'weight_unit' => optional($component->item)->weight_unit,
                'pressure_rating' => optional($component->item)->pressure_rating,
                'pressure_rating_unit' => optional($component->item)->pressure_rating_unit,
                'owner' => optional($component->item)->owner,
                'shear_ram_dist_from_bottom' => optional($component->item)->shear_ram_dist_from_bottom,
                'shear_ram_dist_from_bottom_unit' => optional($component->item)->shear_ram_dist_from_bottom_unit
            ];
        });

        $total_height = $get_all_components->sum('height') ?: 0;

        $component_has_shear = $get_all_components->filter(function ($component) {
            return !is_null($component['shear_ram_dist_from_bottom']);
        });

        if (count($component_has_shear) > 0) {
            if (count($component_has_shear) === 1) {
                $distance_from_lower_shear = $component_has_shear->firstWhere('shear_ram_dist_from_bottom', '!=', null)['shear_ram_dist_from_bottom'];
            } else if (count($component_has_shear) === 2) {
                $lower_shear_component = $component_has_shear->sortBy('shear_ram_dist_from_bottom')->first();
                $upper_shear_component = $component_has_shear->sortByDesc('shear_ram_dist_from_bottom')->first();

                $distance_from_lower_shear = $lower_shear_component['shear_ram_dist_from_bottom'];
                $distance_from_upper_shear = $upper_shear_component['shear_ram_dist_from_bottom'];
            } else {
                $sum_without_max_shear = $component_has_shear->pluck('shear_ram_dist_from_bottom')->filter(function ($value) {
                    return !is_null($value);
                })->sum() - $component_has_shear->max('shear_ram_dist_from_bottom');
                $sum_without_min_shear = $component_has_shear->pluck('shear_ram_dist_from_bottom')->filter(function ($value) {
                    return !is_null($value);
                })->sum() - $component_has_shear->min('shear_ram_dist_from_bottom');
                $distance_from_lower_shear = $total_height + $sum_without_min_shear;
                $distance_from_upper_shear = $total_height + $sum_without_max_shear;
            }
        }

        // Retrieve the reporting history
        $heightPDF = $request->query('height_pdf', 1500);
        $logoBase64 = $this->imageToBase64FromPublic('assets/images/company/company-logo.png');
        $data = [
            'components' => $get_all_components,
            'company_logo' => $logoBase64,
            'reportingHistory' => WellstackReportingHistoryModel::findOrFail($templateId),
            'distance_from_lower_shear' => $distance_from_lower_shear,
            'distance_from_upper_shear' => $distance_from_upper_shear,
            'total_height' => $total_height,
            'total_weight' => $get_all_components->sum('weight') ?: 0,
            'min_psi' => $get_all_components->min('pressure_rating') ?: 0,
        ];

        // Render Blade view to HTML
        $html = view('pdf.wellstack-reporting', $data)->render();

        // Konfigurasi mPDF
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
            'format' => [210, $heightPDF], // A4 size in mm, height can be adjusted
            'tempDir' => sys_get_temp_dir(),
        ];

        // Inisialisasi mPDF
        $mpdf = new Mpdf($mpdfConfig);
        $mpdf->SetAutoPageBreak(false);
        // Tambahkan konten
        $mpdf->WriteHTML($html);

        // Output PDF
        return $mpdf->Output('Well_Stack_Schematic.pdf', 'I');
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
}
