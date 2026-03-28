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
            'data' => Notice::active()->orderByDesc('created_at')->get()
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'expiry_date' => 'nullable|date',
            'is_urgent' => 'nullable|boolean',
        ]);

        $notice = Notice::create(array_merge($validated, [
            'author_id' => \Illuminate\Support\Facades\Auth::id(),
        ]));

        return response()->json([
            'success' => true,
            'data' => $notice
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
            'content' => 'sometimes|required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $notice->update($validated);

        return response()->json([
            'success' => true,
            'data' => $notice
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
