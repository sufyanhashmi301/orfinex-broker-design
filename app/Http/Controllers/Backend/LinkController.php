<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public static function documentLinks()
    {
        return view('backend.links.document');
    }

    public static function platformLinks()
    {
        return view('backend.links.platform');
    }
}
