<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Match2PayService;

class Match2PayController extends Controller
{
    protected $m2p;

    public function __construct(Match2PayService $m2p)
    {
        $this->m2p = $m2p;
    }

    public function testCryptoDeposit(Request $request)
    {
        $data = $request->only([
            'amount',
            'currency',
            'paymentGatewayName',
            'paymentCurrency',
            'tradingAccountLogin',
        ]);

        $response = $this->m2p->createCryptoAgentDeposit($data);

        return response()->json($response);
    }
}
