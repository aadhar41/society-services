<?php

namespace App\Domain\Shared\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class MasterDataController extends Controller
{
    /**
     * Get all active countries.
     */
    public function countries(): JsonResponse
    {
        $countries = Country::where('status', 'Active')->get(['id', 'name', 'countryCode']);
        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }

    /**
     * Get all active states.
     */
    public function states(): JsonResponse
    {
        $states = State::where('status', 'Active')->get(['id', 'state_title']);
        return response()->json([
            'success' => true,
            'data' => $states
        ]);
    }

    /**
     * Get cities by state.
     */
    public function cities(int $stateId): JsonResponse
    {
        $cities = City::where('state_id', $stateId)
            ->where('status', 'Active')
            ->get(['id', 'name']);
            
        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    }
}
