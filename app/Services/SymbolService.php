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
                if($existingSymbol->symbolGroups()->count() > 0) {
                    notify()->error(__('Sorry,Cannot disable this symbol because it is still associated with symbol groups. Please detach first'));
                    return ['success'=>false];
                }
                $existingSymbol->status = 0;
            }else{
                $existingSymbol->status =1;
            }
            $existingSymbol->update();

            notify()->success(__('Status Changed successfully'));
            return ['success'=>false];
        }else {

            $symbol = new Symbol();
            $symbol->symbol_id = $data->Symbol_ID;
            $symbol->symbol = $data->Symbol;
            $symbol->path = $data->Path;
            $symbol->description = $data->Description;
            $symbol->contract_size = $data->ContractSize;
            $symbol->status = 1;
            $symbol->save();
        }
        notify()->success(__('Symbol enabled successfully'));
        return ['success' => true];
    }

    public function storeAllSymbolsFromMt5()
    {
        $symbols = DB::connection('mt5_db')
            ->table('mt5_symbols')
            ->select('Symbol_ID', 'Symbol', 'Path', 'Description', 'ContractSize')
            ->get();

        if (!$symbols) {
            return ['success' => false, 'message' => 'Symbol not found in MT5'];
        }

        $successCount = 0;
        $failureCount = 0;

        foreach ($symbols as $symbol) {
            $existingSymbol = Symbol::where('symbol', $symbol->Symbol)->first();
            if ($existingSymbol) {

                if ($existingSymbol->status == 0) {
                    $existingSymbol->status = 1;
                }

                $existingSymbol->update();
                notify()->success(__('Status Changed successfully'));

            } else {
                $symbolModel = new Symbol();
                $symbolModel->symbol_id = $symbol->Symbol_ID;
                $symbolModel->symbol = $symbol->Symbol;
                $symbolModel->path = $symbol->Path;
                $symbolModel->description = $symbol->Description;
                $symbolModel->contract_size = $symbol->ContractSize;
                $symbolModel->status = 1;
                $symbolModel->save();
                notify()->success(__('Symbol enabled successfully'));
            }

            $successCount++;
        }

        // Return the results
        return [
            'success' => $successCount > 0,
            'success_count' => $successCount,
            'failure_count' => $failureCount
        ];
    }
}
