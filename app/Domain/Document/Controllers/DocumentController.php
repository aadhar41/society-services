<?php

namespace App\Domain\Document\Controllers;

use App\Domain\Document\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DocumentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $docs = Document::with('category')
            ->when($request->category, fn($q, $c) => $q->where('category', $c))
            ->when($request->category_id, fn($q, $id) => $q->where('category_id', $id))
            ->get();

        return response()->json([
            'success' => true,
            'data' => $docs
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:complaint_categories,id',
            'category' => 'nullable|string',
            'file' => 'required|file|max:10240',
        ]);

        $path = $request->file('file')->store('society-docs', 'public');

        $doc = Document::create([
            'society_id' => $request->header('X-Society-Id'), // Use society scope
            'title' => $validated['title'],
            'category_id' => $validated['category_id'] ?? null,
            'category' => $validated['category'] ?? null,
            'file_path' => $path,
            'uploaded_by' => \Illuminate\Support\Facades\Auth::id(),
            'file_type' => $request->file('file')->getMimeType(),
            'file_size' => $request->file('file')->getSize(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $doc->load('category')
        ], 201);
    }

    public function show(Document $document): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $document
        ]);
    }

    public function update(Request $request, Document $document): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'category_id' => 'nullable|exists:complaint_categories,id',
            'category' => 'nullable|string',
        ]);

        $document->update($validated);

        return response()->json([
            'success' => true,
            'data' => $document->load('category')
        ]);
    }

    public function destroy(Document $document): JsonResponse
    {
        $document->delete();
        return response()->json([
            'success' => true,
            'message' => 'Document deleted.'
        ]);
    }
}
