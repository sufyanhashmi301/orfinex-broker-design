<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\PayoutRequest;
use App\Enums\PayoutRequestStatus;

class PayoutRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->status == PayoutRequestStatus::APPROVED){
            $payout_requests = PayoutRequest::where('status', PayoutRequestStatus::APPROVED)->orderBy('id', 'DESC')->get();
        } elseif ($request->status == PayoutRequestStatus::DECLINE) {
            $payout_requests = PayoutRequest::where('status', PayoutRequestStatus::DECLINE)->orderBy('id', 'DESC')->get();
        } elseif ($request->status == PayoutRequestStatus::PENDING) {
            $payout_requests = PayoutRequest::where('status', PayoutRequestStatus::PENDING)->orderBy('id', 'DESC')->get();
        } else {
            
            $payout_requests = PayoutRequest::orderBy('id', 'DESC')->get();
        }

        

        return view('backend.payout_request.index', compact('payout_requests'));
    }

    public function action(Request $request, $payout_request_id) {
        // dd($request->operation, $payout_request_id);

        // do security measures.... such as check if payout request is not pending etc...

        $payout_request = PayoutRequest::find($payout_request_id);
        if($request->operation == 'approve') {
            $payout_request->status = PayoutRequestStatus::APPROVED;

            $wallet = Wallet::where('unique_id', $payout_request->wallet_unique_id)->first();
            $wallet->available_balance = $wallet->available_balance + $payout_request->user_profit_share_amount;
            $wallet->save();

            notify('Payout request approved!', 'Success');

        } else {
            $payout_request->status = PayoutRequestStatus::DECLINE;
            notify('Payout request declined!', 'Success');
        }
        $payout_request->save();

        return redirect()->back();
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
