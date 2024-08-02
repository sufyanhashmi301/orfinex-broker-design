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
        return response()->json($account, 201);
    }

    public function show(SwapFreeAccount $swapFreeAccount)
    {
        return response()->json($swapFreeAccount);
    }

    public function update(StoreSwapFreeAccountRequest $request, SwapFreeAccount $swapFreeAccount)
    {
        $account = $this->swapFreeAccountService->update($request, $swapFreeAccount);
        return response()->json($account);
    }

    public function destroy(SwapFreeAccount $swapFreeAccount)
    {
        $this->swapFreeAccountService->delete($swapFreeAccount);
        return response()->json(null, 204);
    }
}
