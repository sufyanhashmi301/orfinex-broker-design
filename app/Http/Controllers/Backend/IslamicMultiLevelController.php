<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSwapFreeAccountRequest;
use App\Http\Requests\SwapFreeAccountRequest;
use App\Http\Requests\UpdateSwapFreeAccountRequest;
use App\Models\SwapFreeAccount;
use App\Services\SwapFreeAccountService;
use Illuminate\Http\Request;

class IslamicMultiLevelController extends Controller
{
    protected $swapFreeAccountService;

    public function __construct(SwapFreeAccountService $swapFreeAccountService)
    {
        $this->swapFreeAccountService = $swapFreeAccountService;
    }

    public function index()
    {
        $accounts = SwapFreeAccount::all();
        return response()->json($accounts);
    }

    public function store(StoreSwapFreeAccountRequest $request)
    {
        $checkLevelExist = SwapFreeAccount::where('level_order',$request->level_order)->first();
        if($checkLevelExist){
            notify()->error(__('Level already taken.'));
            return redirect()->route('admin.accountType.view',$request->forex_scheme_id);
        }
        $account = $this->swapFreeAccountService->create($request);
        notify()->success(__('Swap free account created successfully.'));
        return redirect()->route('admin.accountType.view',$request->forex_scheme_id);

    }
    public function edit(SwapFreeAccount $swapFreeAccount)
    {

         return view('backend.forex_schema.include.__editSwapFreeForm', compact('swapFreeAccount'))->render();

    }
    public function show(SwapFreeAccount $swapFreeAccount)
    {
        return response()->json($swapFreeAccount);
    }

    public function update(UpdateSwapFreeAccountRequest $request, SwapFreeAccount $swapFreeAccount)
    {
        $checkLevelExist = SwapFreeAccount::where('level_order',$request->level_order)->first();
        if($checkLevelExist){
            notify()->error(__('Level already taken.'));
            return redirect()->route('admin.accountType.view',$request->forex_scheme_id);
        }
        $this->swapFreeAccountService->update($swapFreeAccount, $request->validated());
        notify()->success(__('Swap free account updated successfully.'));
        return redirect()->route('admin.accountType.view',$request->forex_scheme_id);
    }

    public function destroy(SwapFreeAccount $swapFreeAccount)
    {
        $this->swapFreeAccountService->delete($swapFreeAccount);
        notify()->success(__('Swap free account deleted successfully.'));
        return redirect()->route('admin.accountType.view',$swapFreeAccount->forex_scheme_id);
    }
}
