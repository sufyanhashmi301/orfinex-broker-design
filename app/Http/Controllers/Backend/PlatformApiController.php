<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlatformApiController extends Controller
{
    /**
     * Display the cTrader settings page.
     */

     public function __construct()
     {
         $this->middleware('permission:c-trader-display', ['only' => ['cTrader']]);
         $this->middleware('permission:x9-trader-display', ['only' => ['x9Trader']]);
         $this->middleware('permission:db-synchronization-setting', ['only' => ['dbSynchronization']]);
         
 
     }

    public function cTrader()
    {
        return view('backend.setting.platform_api.ctrader');
    }

    public function x9Trader()
    {
        return view('backend.setting.platform_api.x9trader');
    }

    public function dbSynchronization()
    {
        return view('backend.setting.platform_api.db-synchronization');
    }

    // Add more methods as needed for other platform APIs
}
