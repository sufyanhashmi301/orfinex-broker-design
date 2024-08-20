<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSwapBasedAccountRequest;
use App\Http\Requests\UpdateSwapBasedAccountRequest;
use App\Models\MultiLevel;
use App\Services\SwapBasedAccountService;
use Illuminate\Http\Request;

class SwapMultiLevelController extends Controller
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
        $checkLevelExist = MultiLevel::where('type',get_hash($request->type))->where('level_order',$request->level_order)->first();
        if($checkLevelExist){
            notify()->error(__('Level already taken.'));
            return redirect()->route('admin.accountType.view',$request->forex_scheme_id);
        }
        $request->merge(['type'=>get_hash($request->type)]);
//        dd($request->all());
        $account = $this->swapBasedAccountService->create($request);
        notify()->success(__('Swap based account created successfully.'));
        return redirect()->route('admin.accountType.view',$request->forex_scheme_id);

    }
    public function edit(MultiLevel $swapBasedAccount)
    {

         return view('backend.forex_schema.include.__editSwapBasForm', compact('swapBasedAccount'))->render();

    }
    public function show(MultiLevel $swapBasedAccount)
    {
        return response()->json($swapBasedAccount);
    }

    public function update(UpdateSwapBasedAccountRequest $request, MultiLevel $swapBasedAccount)
    {
        $checkLevelExist = MultiLevel::where('level_order',$request->level_order)->first();
        if($checkLevelExist){
            notify()->error(__('Level already taken.'));
            return redirect()->route('admin.accountType.view',$request->forex_scheme_id);
        }
        $this->swapBasedAccountService->update($swapBasedAccount, $request->validated());
        notify()->success(__('Swap based account updated successfully.'));
        return redirect()->route('admin.accountType.view',$request->forex_scheme_id);

    }

    public function destroy(MultiLevel $swapBasedAccount)
    {
        $this->swapBasedAccountService->delete($swapBasedAccount);
        notify()->success(__('Swap based account deleted successfully.'));
        return redirect()->route('admin.accountType.view',$swapBasedAccount->forex_scheme_id);
    }
}
