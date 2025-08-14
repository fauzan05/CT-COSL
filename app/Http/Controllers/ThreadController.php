<?php

namespace App\Http\Controllers;

use App\Models\ThreadModel;
use App\Models\User;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function storeThread(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'sizes' => 'nullable|array',
        ]);

        $thread = new \App\Models\ThreadModel($validated);
        $thread->created_by = $request->user()->id ?? null; // Assuming you have user authentication
        $thread->updated_by = $request->user()->id ?? null; // Assuming you have user authentication
        $thread->save();

        if (isset($validated['sizes']) && is_array($validated['sizes'])) {
            foreach ($validated['sizes'] as $size) {
                $threadSize = new \App\Models\ThreadSizeModel([
                    'thread_id' => $thread->id,
                    'top_connection' => $size['top_connection'] ?? null,
                    'bottom_connection' => $size['bottom_connection'] ?? null,
                    'created_by' => $request->user()->id ?? null,
                    'updated_by' => $request->user()->id ?? null,
                ]);
                $threadSize->save();
            }
        }

        return response()->json(['message' => 'Thread created successfully', 'thread' => $thread], 201);
    }

    public function updateThread(Request $request, $id)
    {
        $thread = \App\Models\ThreadModel::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'sizes' => 'nullable|array',
        ]);

        // Update thread
        $thread->type = $validated['type'];
        $thread->updated_by = $request->user()->id ?? null;
        $thread->save();

        // If sizes are sent, update them
        if (!empty($validated['sizes'])) {
            // Optional: delete existing
            $thread->sizes()->delete();

            // Re-create all sizes
            foreach ($validated['sizes'] as $sizeData) {
                $thread->sizes()->create([
                    'top_connection' => $sizeData['top_connection'],
                    'bottom_connection' => $sizeData['bottom_connection'],
                    'created_by' => $request->user()->id ?? null,
                    'updated_by' => $request->user()->id ?? null,
                ]);
            }
        }

        return response()->json(['message' => 'Thread updated successfully'], 200);
    }

    public function getThreads(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        // Query builder
        $query = ThreadModel::with(['sizes']);

        // Apply filters if any
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('type', 'like', '%' . $search . '%');
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        // Apply sorting
        if ($sortBy == 'total_size') {
            $query->withCount('sizes')
                ->orderBy('sizes_count', $sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        // Paginate the results
        $threads = $query->paginate($perPage);
        // transform items in the paginator
        $threads->getCollection()->transform(function ($thread) {
            return [
                'id' => $thread->id,
                'type' => $thread->type,
                'created_at' => $thread->created_at,
                'updated_at' => $thread->updated_at,
                'updated_by_name' => $thread->updatedByUser ? $thread->updatedByUser->fullname : null,
                'total_sizes' => $thread->sizes->count(),
                'sizes' => $thread->sizes->map(function ($size) {
                    return [
                        'id' => $size->id,
                        'top_connection' => $size->top_connection,
                        'bottom_connection' => $size->bottom_connection,
                    ];
                }),
            ];
        });

        // return the full paginator object as JSON
        return response()->json($threads, 200);
    }

    public function storeThreadSize(Request $request)
    {
        $validated = $request->validate([
            'thread_id' => 'required|exists:threads,id',
            'top_connection' => 'nullable|string|max:255',
            'bottom_connection' => 'nullable|string|max:255',
            'created_by' => 'nullable|integer',
            'updated_by' => 'nullable|integer',
        ]);

        $threadSize = new \App\Models\ThreadSizeModel($validated);
        $threadSize->save();

        return response()->json(['message' => 'Thread size created successfully', 'thread_size' => $threadSize], 201);
    }

    public function getThreadSizes($threadId)
    {
        $thread = \App\Models\ThreadModel::with('sizes')->findOrFail($threadId);
        return response()->json(['thread' => $thread], 200);
    }

    public function deleteThread(Request $request)
    {
        $ids  = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['message' => 'No thread IDs provided'], 400);
        }

        $deletedCount = ThreadModel::whereIn('id', $ids)->delete();

        if ($deletedCount > 0) {
            return response()->json(['message' => 'Threads deleted successfully', 'deleted_count' => $deletedCount], 200);
        } else {
            return response()->json(['message' => 'No threads found for the provided IDs'], 404);
        }
    }

    public function searchThreads(Request $request)
    {
        $query = $request->input('query', '');

        $threads = ThreadModel::with('sizes')
            ->where('type', 'like', '%' . $query . '%')
            ->orWhereHas('sizes', function ($q) use ($query) {
                $q->where('top_connection', 'like', '%' . $query . '%')
                    ->orWhere('bottom_connection', 'like', '%' . $query . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($threads, 200);
    }

    public function getThreadsNoPaginate()
    {
        $search = request()->input('search', '');
        $threads = ThreadModel::with('sizes')
            ->when($search, function ($query, $search) {
                return $query->where('type', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // transform items in the collection
        $threads->transform(function ($thread) {
            return [
                'id' => $thread->id,
                'type' => $thread->type,
                'created_at' => $thread->created_at,
                'updated_at' => $thread->updated_at,
                'updated_by_name' => $thread->updatedByUser ? $thread->updatedByUser->fullname : null,
                'total_sizes' => $thread->sizes->count(),
                'sizes' => $thread->sizes->map(function ($size) {
                    return [
                        'id' => $size->id,
                        'top_connection' => $size->top_connection,
                        'bottom_connection' => $size->bottom_connection,
                    ];
                }),
            ];
        });

        return response()->json($threads, 200);
    }

    public function getThreadSizesById(Request $request, $id)
    {
        $search = $request->input('search', '');
        $limit = $request->input('limit', '');
        $threadSizes = \App\Models\ThreadSizeModel::where('thread_id', $id)
            ->when($search, function ($query, $search) {
                return $query->where('top_connection', 'like', '%' . $search . '%')
                    ->orWhere('bottom_connection', 'like', '%' . $search . '%');
            })
            ->when($limit, function ($query, $limit) {
                return $query->limit($limit);
            })
            ->get();

        return response()->json([
            'data' => $threadSizes->map(function ($size) {
                return [
                    'id' => $size->id,
                    'top_connection' => $size->top_connection,
                    'bottom_connection' => $size->bottom_connection,
                    'updated_at' => $size->updated_at,
                    'updated_by' => $size->updated_by,
                    'updated_by_name' => $size->updatedByUser ? $size->updatedByUser->fullname : null,
                ];
            }),
        ], 200);
    }
}
