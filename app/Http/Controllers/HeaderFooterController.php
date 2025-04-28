<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class HeaderFooterController extends Controller
{
    /**
     * Header and Footer settings Index
     */
    public function index() {
        return view('backend.setting.site_setting.header_footer');
    }

    /**
     * Header and Footer Update settings
     */
    public function updateSettings(Request $request) {
        $settings = [
            'site_admin_header_code' => htmlspecialchars_decode($request->site_admin_header_code ?? setting('site_admin_header_code', 'defaults')),
            'site_admin_footer_code' => htmlspecialchars_decode($request->site_admin_footer_code ?? setting('site_admin_footer_code', 'defaults')),
            'site_user_header_code' => htmlspecialchars_decode($request->site_user_header_code ?? setting('site_user_header_code', 'defaults')),
            'site_user_footer_code' => htmlspecialchars_decode($request->site_user_footer_code ?? setting('site_user_footer_code', 'defaults')),
        ];
    
        foreach ($settings as $name => $value) {
            Setting::updateOrCreate(
                ['name' => $name],
                ['val' => $value]
            );
        }

        notify()->success('Header and Footer updated Successfully!');
        return redirect()->back();
    }
}
