<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SetTune;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
    public function notificationTune(Request $request)
    {
        $type = $request->get('type');
        $key = $type . '_notification_tune';

        $tune = DB::table('settings')->where('name', $key)->value('val');

        if (!$tune) {
            $setTune = SetTune::where('status', 1)->first();
            if ($setTune && $setTune->tune) {
                $tune = asset($setTune->tune);
            }
        }

        return $tune;
    }
}
