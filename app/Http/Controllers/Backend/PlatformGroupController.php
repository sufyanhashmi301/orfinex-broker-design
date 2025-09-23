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
        $this->middleware('permission:risk-book-list', ['only' => ['getRiskBook']]);
         $this->middleware('permission:risk-book-action', ['only' => ['updateRiskBook']]);
         $this->middleware('permission:mt5-group-list', ['only' => ['index']]);
         $this->middleware('permission:manual-group-list', ['only' => ['manualGroupListing']]);
         $this->middleware('permission:manual-group-create', ['only' => ['storeManualGroup']]);
         $this->middleware('permission:manual-group-edit', ['only' => ['updateManualGroup']]);
         $this->middleware('permission:manual-group-delete', ['only' => ['deleteManualGroup']]);
         $this->middleware('permission:mt5-groups-delete', ['only' => [ 'resetAll']]);

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
        return view('backend.platform_group.metatrader', compact('groups', 'riskBooks'));
    }

    public function store(Request $request)
    {
        $groupId = $request->id;
        $status = $request->status; // Boolean value indicating whether to enable or disable

        // Check if the group already exists
        $group = PlatformGroup::where('group_id', $groupId)->first();

        if (!$group) {

            $result = $this->platformGroupService->createGroupFromMt5($groupId);
            return response()->json(['success' => $result['success'], 'message' => $result['message'] ?? 'Group created successfully.']);

        } elseif ($group && $group->status == 0) {

            $result = $this->platformGroupService->updateGroupStatus($groupId, 1); // 1 for enabled
            return response()->json(['success' => $result['success'], 'message' => 'Group enabled successfully.']);

        } elseif ($group && $group->status == 1) {

            $result = $this->platformGroupService->updateGroupStatus($groupId, 0); // 0 for disabled
            return response()->json(['success' => $result['success'], 'message' => 'Group disabled successfully.']);

        }
    }

    public function storeManualGroup(Request $request)
    {
        $validatedData = $request->validate([
            'group' => 'required|string|max:255',
            'currency' => 'required|string|max:255',
            'currencyDigits' => 'required|string|max:255',
            'trader_type' => 'nullable|string|max:255',
            'source_type' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);

        $existingGroup = PlatformGroup::where('group', $validatedData['group'])
            ->where('trader_type', $validatedData['trader_type'])
            ->first();

        if ($existingGroup) {
            notify()->error('Group and trader type combination already exists');
            return redirect()->back();
        }

        $group = new PlatformGroup();
        $group->risk_book_id = 0;
        $group->group = $validatedData['group'];
        $group->currency = $validatedData['currency'];
        $group->currencyDigits = $validatedData['currencyDigits'];
        $group->trader_type = $validatedData['trader_type'];
        $group->source_type = $validatedData['source_type'];
        $group->status = $validatedData['status'];
        $group->save();

        notify()->success('Group created successfully');
        return redirect()->back();
    }

    public function manualGroupListing()
    {
        $groups = PlatformGroup::where('source_type', 'manual')->get();
        return view('backend.platform_group.manual', compact('groups'));
    }

    public function editManualGroup($id)
    {
        $group = PlatformGroup::find($id);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        return view('backend.platform_group.include.__edit_manual_form', compact('group'))->render();
    }

    public function updateManualGroup(Request $request, $id)
    {
        $group = PlatformGroup::find($id);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        $group->update($request->all());
        notify()->success('Group updated successfully');
        return redirect()->back();
    }

    public function deleteManualGroup($id)
    {
        $group = PlatformGroup::find($id);

        if ($group->risk_book_id !== 0) {
            notify()->error(__('Cannot delete platform group as it is linked to a risk book.'));
            return redirect()->back();
        }

        $group->delete();
        notify()->success(__(' Platform group deleted successfully.'));
        return redirect()->back();

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
        $unAssignedGroups = PlatformGroup::where('status', 1)->where('risk_book_id', 0)->get();

        return view('backend.platform_group.risk_book', compact('riskBooks', 'unAssignedGroups'));
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


    public function resetAll(Request $request)
    {
        // Delete all platform groups (both MT5-synced and manual)
        PlatformGroup::query()->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => __('All platform groups have been reset.')]);
        }

        notify()->success(__('All platform groups have been reset.'));
        return redirect()->back();
    }
}
