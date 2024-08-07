<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSwapBasedAccountRequest;
use App\Http\Requests\UpdateSwapBasedAccountRequest;
use App\Models\SwapBasedAccount;
use App\Services\SwapBasedAccountService;
use Illuminate\Http\Request;

class SwapBasedAccountController extends Controller
{
    protected $swapBasedAccountService;

    public function __construct(SwapBasedAccountService $swapBasedAccountService)
    {
        $this->swapBasedAccountService = $swapBasedAccountService;
    }

    public function index()
    {
        $accounts = SwapBasedAccount::all();
        return response()->json($accounts);
    }

    public function store(StoreSwapBasedAccountRequest $request)
    {
        $checkLevelExist = SwapBasedAccount::where('level_order',$request->level_order)->first();
        if($checkLevelExist){
            notify()->error(__('Level already taken.'));
            return redirect()->route('admin.accountType.view',$request->account_type_id);
        }
        $account = $this->swapBasedAccountService->create($request);
        notify()->success(__('Swap based account created successfully.'));
        return redirect()->route('admin.accountType.view',$request->account_type_id);
        
    }
    public function edit(SwapBasedAccount $swapBasedAccount)
    {
    
         return view('backend.forex_schema.include.__editSwapBasForm', compact('swapBasedAccount'))->render();
       
    }
    public function show(SwapBasedAccount $swapBasedAccount)
    {
        return response()->json($swapBasedAccount);
    }

    public function update(UpdateSwapBasedAccountRequest $request, SwapBasedAccount $swapBasedAccount)
    {
        $checkLevelExist = SwapBasedAccount::where('level_order',$request->level_order)->first();
        if($checkLevelExist){
            notify()->error(__('Level already taken.'));
            return redirect()->route('admin.accountType.view',$request->account_type_id);
        }
        $this->swapBasedAccountService->update($swapBasedAccount, $request->validated());
        notify()->success(__('Swap based account updated successfully.'));
        return redirect()->route('admin.accountType.view',$request->account_type_id);
       
    }

    public function destroy(SwapBasedAccount $swapBasedAccount)
    {
        $this->swapBasedAccountService->delete($swapBasedAccount);
        notify()->success(__('Swap based account deleted successfully.'));
        return redirect()->route('admin.accountType.view',$swapBasedAccount->account_type_id);
    }
}
