<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public static function serverSetting()
    {
        return view('backend.server_settings.server.index');
    }
}
