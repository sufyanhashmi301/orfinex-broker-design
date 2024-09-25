<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ForexApiService;

class PositionController extends Controller
{

    protected $forexApiService;

    public function __construct(ForexApiService $forexApiService)
    {
        $this->forexApiService = $forexApiService;
    }

    public function getGroupPosition(Request $request) {
        $group = $request->input('group');
        $response = $this->forexApiService->getPositionsByGroup($group);

        if (is_array($response)) {
            return response()->json($response);
        }

        return response()->json(json_decode($response, true));
    }

    public function positionByDays(Request $request) {
        $days = $request->input('days');
        $response = $this->forexApiService->getPositionsByDays($days);

        if (is_array($response)) {
            return response()->json($response);
        }

        return response()->json(json_decode($response, true));
    }

    public function getPositionByAccount(Request $request) {
        $login = $request->input('login');
        $response = $this->forexApiService->getClientPositionSummary($login);

        if (is_array($response)) {
            return response()->json($response);
        }

        return response()->json(json_decode($response, true));
    }

    public function getGroupNetPosition(Request $request) {
        $group = $request->input('group');
        $response = $this->forexApiService->getGroupPositionSummary($group);

        if (is_array($response)) {
            return response()->json($response);
        }

        return response()->json(json_decode($response, true));
    }


}
