<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSwapBasedAccountRequest;
use App\Http\Requests\UpdateSwapBasedAccountRequest;
use App\Models\MultiLevel;
use App\Models\RebateRule;
use App\Models\IbGroup;
use App\Models\ReferralRelationship;
use App\Models\Symbol;
use App\Models\PlatformGroup;
use App\Services\SwapBasedAccountService;
use Illuminate\Http\Request;

class MultiLevelController extends Controller
{
    protected $swapBasedAccountService;

    public function __construct(SwapBasedAccountService $swapBasedAccountService)
    {
        $this->swapBasedAccountService = $swapBasedAccountService;
    }

    public function index()
    {
        $accounts = MultiLevel::all();
        return response()->json($accounts);
    }

    public function store(StoreSwapBasedAccountRequest $request)
    {
//        dd($request->all());
        $checkLevelExist = MultiLevel::where('type', get_hash($request->type))->where('forex_scheme_id', $request->forex_scheme_id)->where('level_order', $request->level_order)->first();
        if ($checkLevelExist) {
            notify()->error(__('Level already taken.'));
            return redirect()->route('admin.multi-level.view', $request->forex_scheme_id);
        }

        //validate Duplicate symbols
//        $this->validateDuplicateSymbols($request);

        $request->merge(['type' => get_hash($request->type)]);
        $this->swapBasedAccountService->create($request);
        notify()->success(__('Multi Level Account created successfully.'));
        return redirect()->route('admin.multi-level.view', $request->forex_scheme_id);

    }

    public function validateDuplicateSymbols($request)
    {
        $rebateRuleIds = $request->rebate_rules;

        // Load the symbols associated with the selected rebate rules
        $symbols = Symbol::whereHas('symbolGroups.rebateRule', function ($query) use ($rebateRuleIds) {
            $query->whereIn('rebate_rules.id', $rebateRuleIds);
        })->with('symbolGroups.rebateRule')->get();
//        dd($symbols);

        // Check for duplicate symbols
        $symbolNames = $symbols->pluck('symbol')->toArray();
        $duplicates = array_unique(array_diff_assoc($symbolNames, array_unique($symbolNames)));

        if (!empty($duplicates)) {
            // If duplicates exist, return with an error message
            $duplicateSymbols = implode(', ', $duplicates);
            notify()->error(__('Symbols duplicate of :symbols.', ['symbols' => $duplicateSymbols]));
            return redirect()->back();
        }
    }
    public function edit($id)

    {
        $multiLevelAccount = MultiLevel::with('rebateRule', 'ibGroups')->findOrFail($id);
        $rebateRules = RebateRule::where('status',true)->orderBy('title','asc')->get();
        $ibGroups = IbGroup::where('status', 1)->orderBy('name', 'asc')->get();
        $platformGroups = PlatformGroup::where('status', 1)->where('risk_book_id', 0)->get();
        return view('backend.multi_level.include.__editSwapBasForm', compact('multiLevelAccount','rebateRules', 'ibGroups', 'platformGroups'))->render();

    }

    public function show(MultiLevel $swapBasedAccount)
    {
        return response()->json($swapBasedAccount);
    }

    public function update(UpdateSwapBasedAccountRequest $request, MultiLevel $swapBasedAccount,$id)
    {
//        dd($swapBasedAccount);
//        dd($request->all(),$swapBasedAccount,$id);
        $checkLevelExist = MultiLevel::where('id','<>' ,$id)->where('type', get_hash($request->type))->where('forex_scheme_id', $request->forex_scheme_id)->where('level_order', $request->level_order)->exists();
        if ($checkLevelExist) {
            notify()->error(__('Level already taken.'));
            return redirect()->route('admin.multi-level.view', $request->forex_scheme_id);
        }
        // $multiLevel->ibGroups()->sync($data['ib_group_id']);
//        dd($request->validated());
        $this->swapBasedAccountService->update($swapBasedAccount, $request->validated(),$id);
        notify()->success(__('Multi Level Account updated successfully.'));
        return redirect()->route('admin.multi-level.view', $request->forex_scheme_id);

    }

    public function destroy($id)
    {
        $referral = ReferralRelationship::where('multi_level_id',$id)->exists();
        if ($referral) {
            notify()->error(__('Sorry, there is already someone assigned to this level. Please remove that user first!'));
            return redirect()->back();
        }
        $swapBasedAccount =  $this->swapBasedAccountService->delete($id);
        notify()->success(__('Multi Level Account deleted successfully.'));
        return redirect()->route('admin.multi-level.view', $swapBasedAccount->forex_scheme_id);
    }
}
