<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    
    public static function legalLinks()
    {
        $settings = Setting::all();
        return view('backend.links.legal', compact('settings'));
    }

    public static function platformLinks()
    {
        return view('backend.links.platform');
    }
}
