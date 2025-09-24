<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ForexSchema;
use App\Models\IbGroup;
use App\Models\RebateRule;
use App\Models\User;
use App\Models\UserIbRule;
use App\Traits\NotifyTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IBGroupExport;

class IBGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */

    public function __construct()
     {
         $this->middleware('permission:ib-group-list|ib-group-create|ib-group-edit', ['only' => ['index', 'store']]);
         $this->middleware('permission:ib-group-create', ['only' => ['store']]);
         $this->middleware('permission:ib-group-edit', ['only' => ['update']]);
         $this->middleware('permission:ib-group-delete', ['only' => ['destroy']]);
     }


    public function index(Request $request)
    {
        $query = IBGroup::with(['rebateRules.forexSchemas']);
        if ($request->ajax() || $request->hasAny(['global_search', 'status', 'global_account', 'filter_group'])) {
        // Global search
        if ($request->filled('global_search')) {
            $search = $request->global_search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('rebateRules', function($subQuery) use ($search) {
                      $subQuery->where('title', 'like', "%{$search}%");
                  })
                  ->orWhereHas('rebateRules.forexSchemas', function($subQuery) use ($search) {
                      $subQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by global account type
        if ($request->filled('global_account')) {
            $query->where('is_global_account', $request->global_account);
        }

        // Handle direct filter links
        if ($request->filled('filter_group')) {
            $query->where('id', $request->filter_group);
        }
    }
        $ibGroups = $query->paginate(10);
        $rebateRules = \App\Models\RebateRule::select('id', 'title')->get();
        $accountTypes = \App\Models\ForexSchema::select('id', 'title')->get();

        if ($request->ajax()) {
            $html = view('backend.ib_group.index', compact('ibGroups', 'rebateRules', 'accountTypes'))->render();
            return response()->json(['html' => $html]);
        }

        return view('backend.ib_group.index', compact('ibGroups', 'rebateRules', 'accountTypes'));
    }

    public function export(Request $request)
    {
        $query = IBGroup::with(['rebateRules.forexSchemas']);

        // Global search
        if ($request->filled('global_search')) {
            $search = $request->global_search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('rebateRules', function($subQuery) use ($search) {
                      $subQuery->where('title', 'like', "%{$search}%");
                  })
                  ->orWhereHas('rebateRules.forexSchemas', function($subQuery) use ($search) {
                      $subQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by global account type
        if ($request->filled('global_account')) {
            $query->where('is_global_account', $request->global_account);
        }

        // Handle direct filter links
        if ($request->filled('filter_group')) {
            $query->where('id', $request->filter_group);
        }

        $ibGroups = $query->get();

        return Excel::download(new IBGroupExport($ibGroups), 'ib-groups-' . date('Y-m-d') . '.xlsx');
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return view('backend.ib_group.create'); // Return the create form view
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:ib_groups,name',
            'status' => 'required|boolean',
            'is_global_account' => 'required|boolean',
            'rebate_rule_id.*' => 'nullable|exists:rebate_rules,id',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $rebateRuleIds = $request->input('rebate_rule_id', []);

        $request['desc'] = str_replace(['{', '}'], ['<', '>'], $request->desc);

        // Create the IB Group
        $ibGroup = IbGroup::create($request->only(['name', 'desc', 'is_global_account']) + ['status' => $request->input('status', 1)]);
        // Attach Rebate Rules
        $ibGroup->rebateRules()->sync($rebateRuleIds);

        // Manage User IB Rules
        $users = $ibGroup->users; // Assuming there's a `users` relationship in `IbGroup`
        foreach ($users as $user) {
            $this->manageUserRebateRules($user, $ibGroup->id);
        }

        notify()->success($ibGroup->name . ' ' . __('IB Group Created'));
        return redirect()->route('admin.ib-group.index');
    }





    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $ibGroup = IbGroup::find($id);
        if (!$ibGroup) {
            notify()->error(__('IB Group not found'), 'Error');
            return redirect()->route('admin.ib-group.index');
        }

        $rebateRules = RebateRule::where('status', 1)->get(); // Get active rebate rules
        return view('backend.ib_group.modal.__edit_form', compact('ibGroup', 'rebateRules'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:ib_groups,name,' . $id,
            'status' => 'required|boolean',
            'is_global_account' => 'required|boolean',
            'rebate_rule_id.*' => 'nullable|exists:rebate_rules,id',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $ibGroup = IbGroup::find($id);
        if (!$ibGroup) {
            notify()->error(__('IB Group not found'), 'Error');
            return redirect()->route('admin.ib-group.index');
        }

        $rebateRuleIds = $request->input('rebate_rule_id', []);

        $request['desc'] = str_replace(['{', '}'], ['<', '>'], $request->desc);

        // Update IB Group details
        $ibGroup->update($request->only(['name', 'desc', 'status', 'is_global_account']));
        // Attach Rebate Rules
        $ibGroup->rebateRules()->sync($rebateRuleIds);

        // Manage User IB Rules
        $users = $ibGroup->users;
        foreach ($users as $user) {
            $this->manageUserRebateRules($user, $ibGroup->id);
        }

        notify()->success($ibGroup->name . ' ' . __('IB Group Updated'));
        return redirect()->route('admin.ib-group.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            $ibGroup = IbGroup::find($id);
            
            if (!$ibGroup) {
                return response()->json([
                    'success' => false,
                    'message' => 'IB Group not found'
                ], 404);
            }
            
            // Check for attached users
            $users = User::where('ib_group_id', $id)
                        ->select('id', 'username', 'email', 'first_name', 'last_name')
                        ->get();
            
            if ($request->check_users) {
                return response()->json([
                    'users' => $users,
                    'user_count' => $users->count()
                ]);
            }
            
            if ($users->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete IB Group because there are users still attached to it.'
                ], 422);
            }
            
            // Proceed with deletion
            $ibGroup->rebateRules()->sync([]);
            $ibGroup->delete();
            
            return response()->json([
                'success' => true,
                'message' => __('IB Group Deleted Successfully')
            ]);
            
        } catch (\Exception $e) {
            \Log::error("IB Group Deletion Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request.'
            ], 500);
        }
    }
    protected function manageUserRebateRules($user, $ibGroup)
    {
        if (!$ibGroup) {
            // Delete all UserIbRules for the user if no IB Group is provided
            UserIbRule::where('user_id', $user->id)->delete();
            return;
        }

        // Fetch all rebate rules associated with the IB Group
        $rebateRules = RebateRule::whereHas('ibGroups', function ($query) use ($ibGroup) {
            $query->where('ib_groups.id', $ibGroup);
        })->get();

        // Fetch existing UserIbRules for the user and IB Group
        $existingRules = UserIbRule::where('user_id', $user->id)
            ->where('ib_group_id', $ibGroup)
            ->get()
            ->keyBy('rebate_rule_id'); // Key by rebate_rule_id for easier comparison

        $rebateRuleIds = $rebateRules->pluck('id')->toArray();

        // Find missing rules that need to be added
        $missingRules = array_diff($rebateRuleIds, $existingRules->keys()->toArray());

        foreach ($missingRules as $missingRuleId) {
            UserIbRule::create([
                'user_id' => $user->id,
                'ib_group_id' => $ibGroup,
                'rebate_rule_id' => $missingRuleId,
                // Add additional fields if needed, e.g., 'sub_ib_share' => $value
            ]);
        }

        // Find extra rules that need to be removed
        $extraRules = array_diff($existingRules->keys()->toArray(), $rebateRuleIds);

        foreach ($extraRules as $extraRuleId) {
            $existingRules[$extraRuleId]->delete();
        }
    }


}
