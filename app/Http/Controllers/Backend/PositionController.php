<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ForexApiService;
use DataTables;

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
        $positions = $response['result'];

        return DataTables::of($positions)
            ->addIndexColumn()
            ->addColumn('action', 'backend.control_center.include.__action')
            ->addColumn('reason', 'backend.control_center.include.__reason')
            ->rawColumns(['action', 'reason'])
            ->make(true);
    }

    public function positionByDays(Request $request) {
        $days = $request->input('days');
        $response = $this->forexApiService->getPositionsByDays($days);

        $positions = $response['result'];

        return DataTables::of($positions)
            ->addIndexColumn()
            ->addColumn('action', 'backend.control_center.include.__action')
            ->addColumn('reason', 'backend.control_center.include.__reason')
            ->rawColumns(['action', 'reason'])
            ->make(true);
    }

    public function getPositionByAccount(Request $request) {
        $login = $request->input('login');
        $response = $this->forexApiService->getClientPositionSummary($login);
        $positions = $response['result'];

        return DataTables::of($positions)->make(true);
    }

    public function getGroupNetPosition(Request $request) {
        $group = $request->input('group');
        $response = $this->forexApiService->getGroupPositionSummary($group);
        $positions = $response['result'];

        return DataTables::of($positions)->make(true);
    }


}
