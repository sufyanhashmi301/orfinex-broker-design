<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class KycNoticeController extends Controller
{
    /**
     * Kyc Notice Update settings
     */
    public function updateSettings(Request $request) {
        Setting::updateOrCreate(
            ['name' => 'kyc_notice'],             
            ['val' => $request->kyc_notice]  
        );

        notify()->success('KYC Notice updated Successfully!');
        return redirect()->back();
    }
}
