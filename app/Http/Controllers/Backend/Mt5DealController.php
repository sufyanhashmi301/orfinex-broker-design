<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Mt5Deal;
use Illuminate\Http\Request;

class Mt5DealController extends Controller
{
    public function getDeals($login)
    {
        // Fetch deals by login
        $deals = Mt5Deal::where('login', $login)->get();

        // Return the deals as a JSON response
        return response()->json($deals);
    }
}
