<?php

namespace App\Http\Controllers;

use App\Models\DocumentListModel;
use App\Models\DocumentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function getDocuments(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        // Query the documents with pagination
        $query = DocumentModel::with(['createdBy', 'updatedBy', 'documents']);

        // Apply pagination
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $documents = $query->orderBy($sortBy, $sortDirection)
            ->paginate($perPage);

        $documents->getCollection()->transform(function ($document) {
            return [
                'id' => $document->id,
                'name' => $document->name,
                'description' => $document->description,
                'menu' => $document->menu,
                'document_total' => $document->documents->count(),
                'document_list' => $document->documents->map(function ($doc) {
                    return [
                        'id' => $doc->id,
                        'filename' => $doc->filename,
                        'is_current' => true,
                        'created_by_name' => $doc->createdBy ? $doc->createdBy->fullname : null,
                        'updated_by_name' => $doc->updatedBy ? $doc->updatedBy->fullname : null,
                        'created_at' => $doc->created_at->toDateTimeString(),
                        'updated_at' => $doc->updated_at->toDateTimeString(),
                    ];
                }),
                'created_by_name' => $document->createdBy ? $document->createdBy->fullname : null,
                'updated_by_name' => $document->updatedBy ? $document->updatedBy->fullname : null,
                'created_at' => $document->created_at->toDateTimeString(),
                'updated_at' => $document->updated_at->toDateTimeString(),
            ];
        });

        $itemsArray = $documents->toArray();
        return response()->json($itemsArray);
    }

    public function getDocument($id)
    {
        $document = DocumentModel::with(['createdBy', 'updatedBy'])->findOrFail($id);

        return response()->json([
            'id' => $document->id,
            'name' => $document->name,
            'description' => $document->description,
            'filename' => $document->filename,
            'menu' => $document->menu,
            'created_by_name' => $document->createdBy ? $document->createdBy->fullname : null,
            'updated_by_name' => $document->updatedBy ? $document->updatedBy->fullname : null,
            'created_at' => $document->created_at->toDateTimeString(),
            'updated_at' => $document->updated_at->toDateTimeString(),
        ]);
    }

    public function storeDocument(Request $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'menu' => 'required|string',
                'new_documents' => 'required|array',
                'new_documents.*' => 'file|mimes:pdf,doc,docx,odt,rtf,txt|max:15360',
            ]);

            $document = DocumentModel::create($data + [
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Handle file upload
            if ($request->hasFile('new_documents')) {
                // jika belum ada foldernya buat dulu
                if (!is_dir(storage_path('app/protected/documents'))) {
                    mkdir(storage_path('app/protected/documents'), 0755, true);
                }

                foreach ($data['new_documents'] as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs('protected/documents/', $filename);
                    $filename; // Store the last file's name
                    // simpan nama file ke dalam database
                    DocumentListModel::create([
                        'document_id' => $document->id,
                        'filename' => $filename,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
            } else {
                return response()->json(['error' => 'Document file is required'], 422);
            }

            return response()->json($document, 201);
        });
    }

    public function updateDocument(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $get_current_document = DocumentModel::findOrFail($id);
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'menu' => 'required|string'
            ]);

            $documents = array_map(fn($doc) => json_decode($doc, true), $request->documents ?? []);
            // cari apakah di database ada document yang tidak ada pada $documents
            $existingDocuments = DocumentListModel::where('document_id', $id)->get();
            $documentsToDelete = $existingDocuments->filter(function ($doc) use ($documents) {
                return !in_array($doc->id, array_column($documents, 'id'));
            });

            // delete documents that are not in the request
            foreach ($documentsToDelete as $doc) {
                $doc->delete();
            }

            // Handle file upload
            if ($request->hasFile('new_documents')) {
                // jika belum ada foldernya buat dulu
                if (!is_dir(storage_path('app/protected/documents'))) {
                    mkdir(storage_path('app/protected/documents'), 0755, true);
                }

                foreach ($request->file('new_documents') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs('protected/documents/', $filename);
                    // simpan nama file ke dalam database
                    DocumentListModel::create([
                        'document_id' => $id,
                        'filename' => $filename,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
            } else {
                return response()->json(['error' => 'Document file is required'], 422);
            }

            $get_current_document->update($data + [
                'updated_by' => Auth::id(),
            ]);

            return response()->json($get_current_document);
        });
    }

    public function deleteDocument(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['error' => 'No document IDs provided'], 422);
        }
        $documents = DocumentModel::whereIn('id', $ids)->get();
        if ($documents->isEmpty()) {
            return response()->json(['error' => 'No documents found for the provided IDs'], 404);
        }
        foreach ($documents as $document) {
            // // Delete associated document files
            // $documentFiles = DocumentListModel::where('document_id', $document->id)->get();
            // foreach ($documentFiles as $file) {
            //     $filePath = storage_path('app/protected/documents/' . $file->filename);
            //     if (file_exists($filePath)) {
            //         unlink($filePath); // Delete the file
            //     }
            //     $file->delete(); // Delete the record from the database
            // }
            $document->delete(); // Delete the document record
        }

        return response()->json(['message' => 'Document deleted successfully']);
    }

    public function show($filename)
    {
        $path = storage_path('app/protected/documents/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path); // Atau ->download($path);
    }
}
