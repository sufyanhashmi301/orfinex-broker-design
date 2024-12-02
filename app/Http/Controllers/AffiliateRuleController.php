<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use App\Models\AffiliateRule;
use App\Models\AffiliateRuleConfiguration;
use App\Models\AffiliateRuleLevel;
use Illuminate\Http\Request;

class AffiliateRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $account_types = AccountType::all();
        $affiliate_rule = AffiliateRule::first();
        return view('backend.affiliates.create', compact('account_types', 'affiliate_rule'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate request (another file)

        AffiliateRule::truncate();
        AffiliateRuleConfiguration::truncate();
        AffiliateRuleLevel::truncate();

        // store values in AffiliateRule
        $affiliate_rule = new AffiliateRule();
        $affiliate_rule->name = $request->name;
        $affiliate_rule->for_account_type_ids = json_encode($request->for_account_type_ids);
        // $affiliate_rule->count = '0';
        $affiliate_rule->count_mode = $request->count_mode;
        $affiliate_rule->balance_retention_period = $request->balance_retention_period;
        $affiliate_rule->description = $request->description;
        $affiliate_rule->has_levels = $request->has_levels;
        $affiliate_rule->is_active = $request->is_active;
        $affiliate_rule->save();

        // store values in AffiliateRuleConfig
        foreach($request->affiliate_configs as $affiliate_config) {
            $affiliate_rule_config = new AffiliateRuleConfiguration();
            $affiliate_rule_config->affiliate_rule_id = $affiliate_rule->id;
            $affiliate_rule_config->count_start = $affiliate_config['count_start'];
            $affiliate_rule_config->count_end = $affiliate_config['count_end'];
            $affiliate_rule_config->commission_percentage = $affiliate_config['commission_percentage'];
            $affiliate_rule_config->save();
        }

        // store values in AffiliateRuleLevel
        foreach($request->affiliate_levels as $affiliate_level) {
            $affiliate_rule_level = new AffiliateRuleLevel();
            $affiliate_rule_level->affiliate_rule_id = $affiliate_rule->id;
            $affiliate_rule_level->level = $affiliate_level['level'];
            $affiliate_rule_level->commission_percentage = $affiliate_level['commission_percentage'];
            $affiliate_rule_level->save();
        }
        
        notify('Affiliate Rule Successfully Updated!', 'Success');
        return redirect()->back();


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
