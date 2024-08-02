<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSwapFreeAccountRequest;
use App\Http\Requests\SwapFreeAccountRequest;
use App\Models\SwapFreeAccount;
use App\Services\SwapFreeAccountService;
use Illuminate\Http\Request;

class SwapFreeAccountController extends Controller
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
        $account = $this->swapFreeAccountService->create($request);
        notify()->success(__('Swap free account created successfully.'));
        return redirect()->route('admin.accountType.view',$request->account_type_id);
        
    }
    public function edit(SwapFreeAccount $swapFreeAccount)
    {
    
         return view('backend.forex_schema.include.__editSwapFreeForm', compact('swapFreeAccount'))->render();
       
    }
    public function show(SwapFreeAccount $swapFreeAccount)
    {
        return response()->json($swapFreeAccount);
    }

    public function update(StoreSwapFreeAccountRequest $request, SwapFreeAccount $swapFreeAccount)
    {
        $account = $this->swapFreeAccountService->update($request, $swapFreeAccount);
        notify()->success(__('Swap free account updated successfully.'));
        return redirect()->route('admin.accountType.view',$request->account_type_id);
    }

    public function destroy(SwapFreeAccount $swapFreeAccount)
    {
        $this->swapFreeAccountService->delete($swapFreeAccount);
        notify()->success(__('Swap free account deleted successfully.'));
        return redirect()->route('admin.accountType.view',$swapFreeAccount->account_type_id);
    }
}
