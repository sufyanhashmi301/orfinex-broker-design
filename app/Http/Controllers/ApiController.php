<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id,Request $request)
    {
        $user = User::with('realTradingAccounts')->where('id',$id)->first();
//        dd($user);
        return response()->json(['user' => $user]);

    }
    public function getForex(Request $request)
    {

        $data = $request->except(['URL']);
        $response = Http::retry(3, 100)->get($request->get('URL'), $data);
        return $response->object();

    }
    public function postForex(Request $request)
    {
        $data = $request->except(['URL']);
        $response = Http::retry(3, 100)->post($request->get('URL'), $data);
        return $response->object();

    }

    public function getForex(Request $request)
    {

        $data = $request->except(['URL']);
        $response = Http::retry(3, 100)->get($request->get('URL'), $data);
        return $response->object();

    }
    public function postForex(Request $request)
    {
        $data = $request->except(['URL']);
        $response = Http::retry(3, 100)->post($request->get('URL'), $data);
        return $response->object();

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
