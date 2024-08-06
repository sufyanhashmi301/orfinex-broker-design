<?php

namespace App\Services;

use App\Http\Requests\StoreSymbolRequest;
use App\Models\Symbol;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Request;

class SymbolService
{
    
    public function createSymbolFromMt5($symbolId)
    {
        $data = DB::connection('mt5_db')
            ->table('mt5_symbols')
            ->select('Symbol_ID', 'Symbol', 'Path', 'Description', 'ContractSize')
            ->where('Symbol_ID', $symbolId)
            ->first();
        
        if (!$data) {
            return ['success' => false, 'message' => 'Symbol not found in MT5'];
        }

        $existingSymbol = Symbol::where('symbol', $data->Symbol)->first();
        if ($existingSymbol) {
            if($existingSymbol->status==1){
                $existingSymbol->status =0;
            }else{
                $existingSymbol->status =1;
            }
            $existingSymbol->update();
            
            notify()->success(__('Status Changed successfully'));
            return ['success'=>false];
        }

        $symbol = new Symbol();
        $symbol->symbol_id = $data->Symbol_ID;
        $symbol->symbol = $data->Symbol;
        $symbol->path = $data->Path;
        $symbol->description = $data->Description;
        $symbol->contract_size = $data->ContractSize;
        $symbol->status = 1;
        $symbol->save();
        notify()->success(__('Symbol enabled successfully'));
        return ['success' => true];
    }
}