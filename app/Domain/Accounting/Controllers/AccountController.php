<?php

namespace App\Domain\Accounting\Controllers;

use App\Domain\Accounting\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AccountController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Account::with('group')->get()
        ]);
    }

    public function show(Account $account): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $account->load('group')
        ]);
    }
}
