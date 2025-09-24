<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\GatewayType;
use App\Http\Controllers\Controller;
use App\Models\DepositMethod;
use App\Traits\NotifyTrait;
use App\Traits\Payment;

class GatewayController extends Controller
{
    use NotifyTrait, Payment;

    public function gateway($code)
    {
        $gateway = DepositMethod::code($code)->first();

        if ($gateway->type == GatewayType::Manual->value) {
            $fieldOptions = $gateway->field_options;
            $paymentDetails = $gateway->payment_details;
            
            // Check if request deposit accounts is enabled and this method has custom bank details enabled
            if (setting('deposit_account_mode', 'features') === 'request_deposit_accounts' && $gateway->is_custom_bank_details) {
                $approvedRequest = \App\Models\PaymentDepositRequest::forUser(auth()->id())
                    ->approved()
                    ->first();
                
                if ($approvedRequest && $approvedRequest->bank_details) {
                    // Use approved bank details instead of default payment method details
                    $bankDetails = $approvedRequest->bank_details;
                    $gateway = array_merge($gateway->toArray(), ['credentials' => view('frontend::gateway.include.approved_bank_details', compact('fieldOptions', 'bankDetails'))->render()]);
                } else {
                    $gateway = array_merge($gateway->toArray(), ['credentials' => view('frontend::gateway.include.manual', compact('fieldOptions', 'paymentDetails'))->render()]);
                }
            } else {
                $gateway = array_merge($gateway->toArray(), ['credentials' => view('frontend::gateway.include.manual', compact('fieldOptions', 'paymentDetails'))->render()]);
            }
        }else{
            $gatewayCurrency =  is_custom_rate($gateway->gateway->gateway_code) ?? $gateway->currency;
            $gateway['currency'] = $gatewayCurrency;
        }
//        dd($gateway);
        return $gateway;
    }

    //list json
    public function gatewayList()
    {
        $gateways = DepositMethod::where('status', 1)->get();

        return view('frontend::gateway.include.__list', compact('gateways'));
    }
}
