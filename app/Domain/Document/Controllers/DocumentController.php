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
        $docs = Document::when($request->category, fn($q, $c) => $q->where('category', $c))
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
            'category' => 'required|string',
            'file' => 'required|file|max:10240',
        ]);

        $path = $request->file('file')->store('society-docs', 'public');

        $doc = Document::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'file_path' => $path,
            'file_type' => $request->file('file')->getMimeType(),
            'file_size' => $request->file('file')->getSize(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $doc
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
        $document->update($request->only('title', 'category'));
        return response()->json([
            'success' => true,
            'data' => $document
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
