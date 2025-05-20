<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Discount;
use App\Models\UserOffer;
use Illuminate\Http\Request;
use App\Http\Requests\OfferRequest;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{

    public function userIndex() {
        $userOffers = UserOffer::where('user_id', Auth::id())->orderBy('status', 'ASC')->get();
        return view('frontend::offers.index', get_defined_vars());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = Offer::all();
        $discount_codes = Discount::where('purpose', 'offers')->where('status', true)->get();
        return view('backend.setting.promotion.offer.index', compact('offers', 'discount_codes'));
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
    public function store(OfferRequest $request)
    {
        $offer = new Offer();

        $offer->discount_id = $request->discount_id;
        $offer->name = $request->name;
        $offer->condition = $request->condition;
        $offer->description = $request->description;
        $offer->multiple_time_usage = $request->multiple_time_usage;
        $offer->validity = $request->validity;
        $offer->status = $request->status;
        $offer->save();

        notify()->success('Offer Updated Successfully!');
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
        $offer = Offer::findOrFail($id);

        $offer->condition = $request->condition;
        $offer->discount_id = $request->discount_id;
        $offer->name = $request->name;
        $offer->description = $request->description;
        $offer->multiple_time_usage = $request->multiple_time_usage;
        $offer->validity = $request->validity;
        $offer->status = $request->status;
        $offer->save();
        
        notify()->success('Offer Updated Successfully!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $offer = Offer::findOrFail($id);



        $offer->delete();

        notify()->success('Offer Deleted Successfully.');
        return redirect()->back();
    }
}
