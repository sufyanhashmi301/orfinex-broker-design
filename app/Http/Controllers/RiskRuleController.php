<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\RiskRule;
use Illuminate\Http\Request;
use App\Services\RiskRuleService;
use App\Models\AccountTypeInvestment;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

class RiskRuleController extends Controller
{

    protected $risk_rule_service;

    public function __construct(RiskRuleService $risk_rule_service) {
        $this->risk_rule_service = $risk_rule_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function filterApiData($api_data, $login_key) {
        // All Accounts
        $valid_accounts_logins = AccountTypeInvestment::pluck('login')->toArray();

        // Only include the logins that exists on system
        $data = array_filter($api_data, function ($item) use ($valid_accounts_logins, $login_key) {
            return in_array($item[$login_key], $valid_accounts_logins);
        });

        return $data;
    }

    public function runSeeder() {
        Artisan::call('db:seed', [
            '--class' => 'RiskRuleSeeder' 
        ]);
    }

    public function riskRule(Request $request) {
        $risk_rule_slug = explode('.', Route::currentRouteName())[2];

        $risk_rules = RiskRule::all();

        // Run the seeder if DB is not initialized
        if(count($risk_rules) != 5) {
            $this->runSeeder();
            $risk_rules = RiskRule::all();
        }

        // Quick Trades Rule
        $risk_rule = $risk_rules->where('slug', $risk_rule_slug)->first();

        // Get Data
        $data = $this->risk_rule_service->getData($request, $risk_rule, $risk_rule_slug);
    
        // Filter api data
        $login_key = 'loginID';
        if($risk_rule_slug == 'trade_age') {
            $login_key = 'login';
        }
        $data = $this->filterApiData($data, $login_key);

        // All Accounts
        $accounts = AccountTypeInvestment::all();

        // totalTrades Sort
        if($risk_rule_slug == 'scalper_detection' || $risk_rule_slug == 'most_trades') {
            // Sort the data

            if ($request->query('sort') === 'ltoh') {
                // Use collect() to work with the result array
                $sortedResult = collect($data)->sortBy('totalTrades')->values()->all();
            } else {
                // Default sorting: high to low
                $sortedResult = collect($data)->sortByDesc('totalTrades')->values()->all();
            }

            // Update the data with the sorted result
            $data = $sortedResult;

        }

        // ip address example data
        // $data = [
        //     [
        //     "loginID" => 1000,
        //     "registrationTime" => "06/26/2024 13:32:25",
        //     "lastIP" => "104.28.226.98",
        //     "lastAccessTime" => "12/05/2024 18:14:28",
        //   ],
        //   [
        //     "loginID" => 1001,
        //     "registrationTime" => "06/26/2024 14:08:21",
        //     "lastIP" => "180.75.240.219",
        //     "lastAccessTime" => "12/13/2024 03:24:59",
        //   ],
        //   [
        //     "loginID" => 1009,
        //     "registrationTime" => "06/26/2024 14:58:35",
        //     "lastIP" => "175.139.185.201",
        //     "lastAccessTime" => "12/13/2024 05:31:36",
        //   ],
        //   [
        //     "loginID" => 1010,
        //     "registrationTime" => "06/26/2024 16:09:48",
        //     "lastIP" => "104.28.155.219",
        //     "lastAccessTime" => "10/16/2024 12:14:39",
        //   ],
        //   [
        //     "loginID" => 1000,
        //     "registrationTime" => "06/26/2024 16:15:06",
        //     "lastIP" => "104.28.234.125",
        //     "lastAccessTime" => "06/27/2024 13:21:54",
        //   ],
        //   [
        //     "loginID" => 1000,
        //     "registrationTime" => "07/06/2024 15:34:53",
        //     "lastIP" => "134.119.219.58",
        //     "lastAccessTime" => "12/10/2024 01:00:05",
        //   ]
        // ];   

        // Ip Addresses Array Resolve
        if($risk_rule_slug == 'ip_address') {
            $data = $this->ipAddressArrayResolve($data);
        }

        return view('backend.risk_rules.' . $risk_rule_slug, compact('risk_rule', 'data', 'accounts', 'risk_rule_slug'));
    }

    public function updateRiskCriteria(Request $request) {
        
        $criteria_data = $request->only('criteria')['criteria'];
        
        // Validation
        foreach($criteria_data as $criteria) {
            if( $criteria['value'] < 0 ) {
                notify()->error('Value Cannot be less than 0!', 'Error');
                return redirect()->back();
            }
        }   

        $page_slug = $request->page_slug;

        $risk_rule = RiskRule::where('slug', $page_slug)->first();
        $risk_rule->criteria = $criteria_data;
        $risk_rule->save();

        notify('Risk Rule Criteria Updated Successfully!', 'Success');
        return redirect()->route('admin.risk-rule.' . $page_slug, array_merge(
            $request->query(),
            ['criteria_updated' => true]
        ));
    }

    /**
     * IP Address Array Resolve
     */
    public function ipAddressArrayResolve($data) {
        $processedData = [];
        $loginIdMap = [];

        foreach ($data as $entry) {
            $loginID = $entry['loginID'];

            if (!isset($loginIdMap[$loginID])) {
                // First occurrence of this loginID
                $loginIdMap[$loginID] = [
                    'entry' => $entry,
                    'ip_addresses' => [$entry['lastIP']],
                    'ip_count' => 1
                ];
            } else {
                // Duplicate loginID found
                $loginIdMap[$loginID]['ip_addresses'][] = $entry['lastIP'];
                $loginIdMap[$loginID]['ip_count']++;
            }
        }

        // Construct the processed data array
        foreach ($loginIdMap as $loginID => $details) {
            $details['entry']['ip_count'] = $details['ip_count'];
            $details['entry']['ip_addresses'] = array_unique($details['ip_addresses']);
            $processedData[] = $details['entry'];
        }

        // $processedData now contains the required structure
        return $processedData;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
