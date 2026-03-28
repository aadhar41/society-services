<?php

namespace App\Domain\Communication\Controllers;

use App\Domain\Communication\Models\Poll;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Poll::withCount('votes')->get()
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2',
            'expires_at' => 'nullable|date',
        ]);

        $poll = Poll::create($validated);

        return response()->json([
            'success' => true,
            'data' => $poll
        ], 201);
    }

    public function show(Poll $poll): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $poll->load('votes')
        ]);
    }

    public function update(Request $request, Poll $poll): JsonResponse
    {
        $poll->update($request->only('is_active'));
        return response()->json([
            'success' => true,
            'data' => $poll
        ]);
    }

    public function destroy(Poll $poll): JsonResponse
    {
        $poll->delete();
        return response()->json([
            'success' => true,
            'message' => 'Poll deleted.'
        ]);
    }

    /**
     * POST /api/v2/polls/{poll}/vote
     */
    public function vote(Request $request, Poll $poll): JsonResponse
    {
        $validated = $request->validate([
            'option' => 'required|string',
        ]);

        $vote = $poll->votes()->updateOrCreate(
            ['user_id' => \Illuminate\Support\Facades\Auth::id()],
            ['option' => $validated['option']]
        );

        return response()->json([
            'success' => true,
            'message' => 'Vote recorded.',
            'data' => $vote
        ]);
    }

    /**
     * GET /api/v2/polls/{poll}/results
     */
    public function results(Poll $poll): JsonResponse
    {
        $results = $poll->votes()
            ->select('option', DB::raw('count(*) as count'))
            ->groupBy('option')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }
}
