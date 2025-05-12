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
            $setTune = SetTune::where('status', 1)->first();
            if ($setTune && $setTune->tune) {
                $tune = $setTune->tune;
            }
        }

        return asset($tune);
    }
}
