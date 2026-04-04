<?php

namespace App\Domain\Communication\Controllers;

use App\Domain\Communication\Models\Notice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NoticeController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Notice::with('category')->active()->orderByDesc('created_at')->get()
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category_id' => 'nullable|exists:complaint_categories,id',
            'expires_at' => 'nullable|date',
            'priority' => 'nullable|in:low,normal,high',
        ]);

        $notice = Notice::create(array_merge($validated, [
            'category_id' => ($validated['category_id'] ?? null) ?: null,
            'created_by' => \Illuminate\Support\Facades\Auth::id(),
            'published_at' => now(),
        ]));

        return response()->json([
            'success' => true,
            'data' => $notice->load('category')
        ], 201);
    }

    public function show(Notice $notice): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $notice->load('attachments')
        ]);
    }

    public function update(Request $request, Notice $notice): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'body' => 'sometimes|required|string',
            'category_id' => 'nullable|exists:complaint_categories,id',
            'priority' => 'sometimes|required|in:low,normal,high',
            'expires_at' => 'nullable|date',
        ]);

        if (array_key_exists('category_id', $validated)) {
            $validated['category_id'] = ($validated['category_id'] ?? null) ?: null;
        }

        $notice->update($validated);

        return response()->json([
            'success' => true,
            'data' => $notice->load('category')
        ]);
    }

    public function destroy(Notice $notice): JsonResponse
    {
        $notice->delete();
        return response()->json([
            'success' => true,
            'message' => 'Notice deleted.'
        ]);
    }
}
