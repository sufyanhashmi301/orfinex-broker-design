<?php

namespace App\Http\Controllers;

use App\Models\SetTune;

class AppController extends Controller
{
    public function notificationTune(Request $request)
    {
        $type = $request->get('type', 'default');
        $key = $type . '_notification_tune';

        $tune = DB::table('settings')->where('key', $key)->value('value');

        if (!$tune) {
            $tune = DB::table('settings')->where('key', 'default_notification_tune')->value('value');
        }

        return asset($tune);
    }
}
