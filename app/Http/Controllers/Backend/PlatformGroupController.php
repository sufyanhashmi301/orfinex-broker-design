<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PlatformGroup;
use App\Services\PlatformGroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class PlatformGroupController extends Controller
{
    protected $platformGroupService;

    public function __construct(PlatformGroupService $platformGroupService)
    {
        $this->platformGroupService = $platformGroupService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::connection('mt5_db')
                ->table('mt5_groups')
                ->select('Group_ID','Group','Currency','CurrencyDigits','MarginCall','MarginStopOut');
            $existingGroups = PlatformGroup::pluck('group')->toArray();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) use ($existingGroups) {
                    return view('backend.platform_group.include.__action', [
                        'Group_ID' => $row->Group_ID,
                        'Group' => $row->Group,
                        'existingGroups' => $existingGroups
                    ])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.platform_group.index');
    }

    public function store(Request $request)
    {

        $groupId = $request->id;

        $result = $this->platformGroupService->createGroupFromMt5($groupId);

        if ($result['success']) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }

    }

}
