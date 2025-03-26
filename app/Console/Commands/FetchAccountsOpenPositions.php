<?php

namespace App\Console\Commands;

use App\Models\RiskRule;
use App\Enums\TraderType;
use Illuminate\Console\Command;
use App\Services\ForexApiService;
use App\Models\AccountOpenPosition;

class FetchAccountsOpenPositions extends Command
{

    protected $risk_api_call;

    public function __construct(ForexApiService $risk_api_call) {
        parent::__construct(); // Call the parent constructor
        $this->risk_api_call = $risk_api_call;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:accounts-open-positions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the AccountOpenPosition model with the latest Open Positions data. It helps the user to see their currently open positions on the CRM trading stats view.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(setting('active_trader_type') == TraderType::MT5) {
            $request_data = [];
            $api_endpoint = 'position/list/open';
            $api_request_http_method = 'GET';

            // making request
            $api_response = $this->risk_api_call->riskRule($request_data, $api_endpoint, $api_request_http_method);

            // Create new record in AccountOpenPositions
            $account_open_position = new AccountOpenPosition();
            $account_open_position->data = $api_response['result'];
            $account_open_position->save();
            
            // Also update in the open_positions risk rules (courtesy)
            $open_positions_risk_rule = RiskRule::where('slug', 'open_position')->first();
            if($open_positions_risk_rule) {
                $open_positions_risk_rule->data = $api_response['result'];
                $open_positions_risk_rule->save();
            }

            $this->info('Trades Open Positions Fetched and Updated Successfully!');

        } else {
            $this->info('Not Supported for this !');
        }
        
    }
}
