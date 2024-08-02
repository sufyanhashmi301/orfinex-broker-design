<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSwapBasedAccountRequest;
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
        $account = $this->swapBasedAccountService->create($request);
        return response()->json($account, 201);
    }

    public function show(SwapBasedAccount $swapFreeAccount)
    {
        return response()->json($swapFreeAccount);
    }

    public function update(StoreSwapBasedAccountRequest $request, SwapBasedAccount $swapBasedAccount)
    {
        $account = $this->swapBasedAccountService->update($request, $swapBasedAccount);
        return response()->json($account);
    }

    public function destroy(SwapBasedAccount $swapBasedAccount)
    {
        $this->swapBasedAccountService->delete($swapBasedAccount);
        return response()->json(null, 204);
    }
}
