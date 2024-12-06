<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    
    public function __construct()
    {
         $this->middleware('permission:all-sections-settings', ['only' => ['allSections']]);
         $this->middleware('permission:blocklist-ip-settings', ['only' => ['blocklistIP']]);
         $this->middleware('permission:single-session-settings', ['only' => ['singleSession']]);
         $this->middleware('permission:blocklist-email-settings', ['only' => ['blocklistEmail']]);
         $this->middleware('permission:login-expiry-settings', ['only' => ['loginExpiry']]);
    }

    public static function allSections()
    {
        return view('backend.security.all_sections.index');
    }

    public static function blocklistIP()
    {
        return view('backend.security.blocklist_ip.index');
    }

    public static function singleSession()
    {
        return view('backend.security.single_session.index');
    }

    public static function blocklistEmail()
    {
        return view('backend.security.blocklist_email.index');
    }

    public static function loginExpiry()
    {
        return view('backend.security.login_expiry.index');
    }
}
