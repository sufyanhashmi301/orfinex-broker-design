<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RiskHubController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:active-positions|net-positions-accounts|net-positions-groups|older-positions-days', ['only' => ['activePositions', 'netPositionsAccounts', 'netPositionsGroups', 'olderPositionsDays']]);
        $this->middleware('permission:active-positions', ['only' => ['activePositions']]);
        $this->middleware('permission:net-positions-accounts', ['only' => ['netPositionsAccounts']]);
        $this->middleware('permission:net-positions-groups', ['only' => ['netPositionsGroups']]);
        $this->middleware('permission:older-positions-days', ['only' => ['olderPositionsDays']]);
    }

    public function activePositions()
    {
        return view('backend.control_center.active_positions');
    }

    public function netPositionsAccounts()
    {
        return view('backend.control_center.net_positions_accounts');
    }

    public function netPositionsGroups()
    {
        return view('backend.control_center.net_positions_groups');
    }

    public function olderPositionsDays()
    {
        return view('backend.control_center.older_positions_days');
    }
}

