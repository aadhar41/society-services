<?php

namespace App\Domain\Accounting\Controllers;

use App\Domain\Accounting\Models\AccountGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AccountGroupController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => AccountGroup::all()
        ]);
    }
}
