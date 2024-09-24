<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PlatformGroup;
use App\Models\RiskBook;
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
        $groups = PlatformGroup::where('status',true)->get();
        $riskBooks = RiskBook::get();
        return view('backend.platform_group.index', compact('groups', 'riskBooks'));
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

    public function assignRiskBook(Request $request)
    {
        $request->validate([
            'risk_book_id' => 'required|exists:risk_books,id',
            'group_ids' => 'required|array',
            'group_ids.*' => 'exists:groups,id',
        ]);

        $riskBookId = $request->risk_book_id;
        $groupIds = $request->group_ids;

        PlatformGroup::whereIn('id', $groupIds)->update(['risk_book_id' => $riskBookId]);

        notify()->success(__('Risk Book assigned successfully!'));
        return redirect()->route('admin.platformGroups');
    }

    public function getRiskBook()
    {
        $riskBooks = RiskBook::with(['groups' => function ($query) {
            $query->where('status',true);
        }])->get();
        return view('backend.platform_group.risk_book', compact('riskBooks'));
    }

    public function riskBookShow($id)
    {
        $riskBook = RiskBook::with(['groups' => function ($query) {
            $query->where('status',true);
        }])->findOrFail($id);

        $allGroups = PlatformGroup::where('status', 1)->get();

        $selectedGroupIds = $riskBook->groups->pluck('id')->toArray();

        // Return the data in the desired format
        return response()->json([
            'risk_book' => $riskBook,
            'all_groups' => $allGroups,
            'selected_group_ids' => $selectedGroupIds,
        ]);
    }

    public function updateRiskBook(Request $request, $id)
    {
        $request->validate([
            'id' => 'required|exists:risk_books,id',
            'group_ids' => 'required|array',
            'group_ids.*' => 'exists:groups,id',
        ]);

        $riskBookId = $request->id;
        $groupIds = $request->group_ids;

        $unAssignedId = 0;

        PlatformGroup::where('risk_book_id', $id)->update(['risk_book_id' => $unAssignedId]);
        PlatformGroup::whereIn('id', $groupIds)->update(['risk_book_id' => $riskBookId]);

        notify()->success(__('Risk Book updated successfully!'));
        return redirect()->route('admin.platform.riskBook');
    }


}
