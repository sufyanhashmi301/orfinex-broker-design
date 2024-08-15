<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Mt5DealController extends Controller
{
    public function getDeals($login)
    {
//        dd($login);
        $deals = DB::connection('mt5_db')
            ->table('mt5_positions')
            ->where('Login', $login)
            ->limit(12)
            ->get();

        return view('backend.user.include.__open_trades', compact('deals'));
    }
}
