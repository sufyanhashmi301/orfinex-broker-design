<?php

namespace App\Http\Controllers;

use App\Models\AffiliateRuleConfiguration;
use App\Models\User;
use App\Models\UserAffiliate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAffiliateController extends Controller
{

    private function generateReferralId($userName) {
        // Step 1: Get the first letters of the user's name
        $nameParts = explode(' ', $userName); // Split the name by spaces
        $initials = '';
        foreach ($nameParts as $part) {
            $initials .= strtoupper(substr($part, 0, 1)); // Get the first letter of each part
        }
    
        // Step 2: Generate a unique referral ID
        $referralId = '';
        do {
            // Generate a 6-digit random number
            $randomNumber = random_int(100000, 999999);
    
            // Combine initials with the random number
            $referralId = $initials . $randomNumber;
    
            // Check if the referral ID exists in the UserAffiliate table
            $exists = UserAffiliate::where('user_id', $referralId)->exists();
        } while ($exists); // Repeat until the ID is unique
    
        return $referralId;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // create affiliate account, if not have one
        if( !UserAffiliate::where('user_id', Auth::id())->exists() ){
            $user_affiliate_account = new UserAffiliate();
            $user_affiliate_account->user_id = Auth::id();
            $user_affiliate_account->current_balance = 0.00;
            $user_affiliate_account->withdrawable_balance = 0.00;

            $user_affiliate_account->total_purchase_amount = 0.00;
            $user_affiliate_account->total_commission = 0.00;
            $user_affiliate_account->commission_withdrawn = 0.00;
            $user_affiliate_account->commission_pending = 0.00;
            $user_affiliate_account->highest_commission_earned = 0.00;

            $user_affiliate_account->referral_link = $this->generateReferralId( User::find(Auth::id())->first_name . ' ' .  User::find(Auth::id())->last_name );
            $user_affiliate_account->save();
        }

        $referrals = User::where('id', Auth::id())->first()->referrals;
        $affiliate_info = User::find( Auth::id() )->userAffiliate;
        $affiliate_rule_configuration = AffiliateRuleConfiguration::all();
        // dd($affiliate_rule_configuration->where('count_start', '<=', 1)->where('count_end', '>=', 1)->first()->commission_percentage);

        // dd($affiliate_info);
        return view('frontend::affiliates.index', compact('referrals', 'affiliate_info', 'affiliate_rule_configuration'));
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
