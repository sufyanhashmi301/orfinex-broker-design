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

  private function doApiRequest($request, $risk_rule_slug, $risk_rule, $request_data) {
    $data_from = date('d/m/Y', strtotime($request->dataFrom));
    $data_to = date('d/m/Y', strtotime($request->dataTo));

    $request_data = array_merge($request_data, [
      "fromDateTime" => $data_from,
      "toDateTime" => $data_to
    ]);

    if($risk_rule_slug == 'trade_age') {
      $request_data = [
        "Days" => $risk_rule->criteria['Days']['value']
      ];
    }

    if($risk_rule_slug == 'open_positions') {
      $request_data = [];
    }

    $api_response = $this->risk_api_call->riskRule($request_data, $risk_rule->api_endpoint, $risk_rule->api_request_http_method);

    return $api_response['result'];
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
        "timeInSeconds" => $risk_rule->criteria['timeInSeconds']['value']
      ]);
    }

    // if the latest data fecthed is Old and not contain the custom date in URL. If the data is empty then load it anyways
    if(
      // $risk_rule->updated_at < Carbon::now()->subHour() && 
      // !isset($request->dataFrom) &&
      // !isset($request['criteria_updated'])


      1 == 1
    ) {
      $request_data = array_merge($request_data, [
        "fromDateTime" => Carbon::today()->format('d/m/Y'), // "01/12/2024" Carbon::today()->format('d/m/Y')
        "toDateTime" => Carbon::today()->format('d/m/Y')
      ]);
  
      if($risk_rule_slug == 'trade_age') {
        $request_data = [
          "Days" => $risk_rule->criteria['Days']['value']
        ];
      }
      // dd($risk_rule->data);
      $api_response = $this->risk_api_call->riskRule($request_data, $risk_rule->api_endpoint, $risk_rule->api_request_http_method);
  
      $risk_rule->data_from = Carbon::today();
      $risk_rule->data_to = Carbon::today();

      if($risk_rule_slug == 'ip_address') {
        $ip_address_data = $this->updateIpAddressData($risk_rule->data, $api_response['result']);
        
        $risk_rule->data = $ip_address_data;

        $risk_rule->save();
        return $risk_rule->data;

      } else{
        $risk_rule->data = $api_response['result'];
      }

      $risk_rule->save();
      return $api_response['result'];
    }

    

    // if dateFrom and dateTo are set
    if( 
      isset($request->dataFrom) &&
      isset($request->dataTo) &&
      !isset($request['criteria_updated']) &&
      $risk_rule_slug != 'ip_address'
    ) {
      return $this->doApiRequest($request, $risk_rule_slug, $risk_rule, $request_data);
    }

    // if paramters are updated, then do the api request anyways
    if(isset($request['criteria_updated'])) {
      return $this->doApiRequest($request, $risk_rule_slug, $risk_rule, $request_data);
    }
    return $risk_rule->data;
    

    // dd( $api_response );
  }

  /**
   * IP Address Data Resolver
   */
  public function updateIpAddressData(array $existingData, array $apiResponse) {
      foreach ($apiResponse as $apiItem) {
          $loginID = $apiItem['loginID'];
          $lastIP = $apiItem['lastIP'];

          // Find if loginID exists in the existing data
          $existingIndex = array_search($loginID, array_column($existingData, 'loginID'));

          if ($existingIndex === false) {
              // loginID does not exist, add a new record
              $newRecord = [
                  'loginID' => $loginID,
                  'registrationTime' => $apiItem['registrationTime'],
                  'lastIP' => $lastIP,
                  'lastAccessTime' => $apiItem['lastAccessTime'],
                  'ip_addresses' => $lastIP ? [$lastIP] : [], // Add lastIP as first index if not empty
              ];
              $existingData[] = $newRecord;
          } else {
              // loginID exists, update the ip_addresses if needed
              if (!empty($lastIP) && !in_array($lastIP, $existingData[$existingIndex]['ip_addresses'])) {
                  $existingData[$existingIndex]['ip_addresses'][] = $lastIP;
              }
          }
      }

      return $existingData;
  }
}
