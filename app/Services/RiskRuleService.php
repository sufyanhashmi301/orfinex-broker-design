<?php

namespace App\Services;

use Carbon\Carbon;
use App\Services\ForexApiService;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\RequestException;


class RiskRuleService
{
  protected $risk_api_call;

  public function __construct(ForexApiService $risk_api_call) {
    $this->risk_api_call = $risk_api_call;
  }

  public function getData($request, $risk_rule, $risk_rule_slug) {

    $request_data = [];

    // Slug specifics
    if($risk_rule_slug == 'quick_trades') {
      $request_data = array_merge($request_data, [
        "reportFlag" => 0
      ]);
    }
    if($risk_rule_slug == 'quick_trades' || $risk_rule_slug == 'scalper_detection') {
      $request_data = array_merge($request_data, [
        "timeInSeconds" => 120
      ]);
    }

    // if the latest data fecthed is Old and not contain the custom date in URL. If the data is empty then load it anyways
    if(
      $risk_rule->updated_at < Carbon::now()->subHour() && 
      !isset($request->dataFrom)
    ) {
        
        $request_data = array_merge($request_data, [
          "fromDateTime" => Carbon::today()->format('d/m/Y'), // "01/12/2024" Carbon::today()->format('d/m/Y')
          "toDateTime" => Carbon::today()->format('d/m/Y')
        ]);
        $api_response = $this->risk_api_call->riskRule($request_data, $risk_rule->api_endpoint);

        $risk_rule->data_from = Carbon::today();
        $risk_rule->data_to = Carbon::today();
        $risk_rule->data = $api_response['result'];
        $risk_rule->save();
        return $api_response['result'];
    }

    // if dateFrom and dateTo are set
    if( 
      isset($request->dataFrom) &&
      isset($request->dataTo)
    ) {
      
      $data_from = date('d/m/Y', strtotime($request->dataFrom));
      $data_to = date('d/m/Y', strtotime($request->dataTo));

      $request_data = array_merge($request_data, [
        "fromDateTime" => $data_from,
        "toDateTime" => $data_to
      ]);
      $api_response = $this->risk_api_call->riskRule($request_data, $risk_rule->api_endpoint);

      return $api_response['result'];
    }

    return $risk_rule->data;
    

    // dd( $api_response );
  }
}
