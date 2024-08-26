<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSwapBasedAccountRequest;
use App\Http\Requests\UpdateSwapBasedAccountRequest;
use App\Models\MultiLevel;
use App\Models\RebateRule;
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
        $request->merge(['type' => get_hash($request->type)]);
//        dd($request->all());
        $this->swapBasedAccountService->create($request);
        notify()->success(__('Multi Level Account created successfully.'));
        return redirect()->route('admin.multi-level.view', $request->forex_scheme_id);

    }

    public function edit($id)
    {
        $multiLevelAccount = MultiLevel::findOrFail($id);
        $rebateRules = RebateRule::where('status',true)->orderBy('title','asc')->get();

        return view('backend.multi_level.include.__editSwapBasForm', compact('multiLevelAccount','rebateRules'))->render();

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
//        dd($request->validated());
        $this->swapBasedAccountService->update($swapBasedAccount, $request->validated(),$id);
        notify()->success(__('Multi Level Account updated successfully.'));
        return redirect()->route('admin.multi-level.view', $request->forex_scheme_id);

    }

    public function destroy($id)
    {
//        dd($id);
        $swapBasedAccount =  $this->swapBasedAccountService->delete($id);
        notify()->success(__('Multi Level Account deleted successfully.'));
        return redirect()->route('admin.multi-level.view', $swapBasedAccount->forex_scheme_id);
    }
}
