<?php

namespace App\Http\Controllers;

use App\Enums\VoiceCallMethodEnums;
use App\Models\VoiceCall;
use Database\Seeders\VoiceCallSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class VoiceCallController extends Controller
{

    public function runSeeder() {
        Artisan::call('db:seed', [
            '--class' => 'VoiceCallSeeder' 
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $voice_call_methods = VoiceCall::all();
        
        // Run the seeder if DB is not initialized
        if(count($voice_call_methods) != VoiceCallSeeder::$TOTAL_VOICE_CALL_METHODS) {
            $this->runSeeder();
            return redirect()->route('admin.settings.voice_call.index');
        }
        
        return view('backend.setting.communication.voice_call.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function toggleMethod(Request $request, $id) {

        if($request->action == 'active') {
            VoiceCall::query()->update(['status' => 0]);
            VoiceCall::find($id)->update(['status' => 1]);
        }
        
        if($request->action == 'disable') {
            VoiceCall::find($id)->update(['status' => 0]);
        }
        
        notify()->success('Voice Call settings saved successfully!');

        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->method == 'twilio') {
            $details = $request->only(['phone_number', 'verified_phone_number', 'sid', 'auth_token', 'api_key_sid', 'api_key_secret', 'twiml_app_sid']);
            
            $aws_storage_method = VoiceCall::where('method', VoiceCallMethodEnums::TWILIO)->first();
            $aws_storage_method->details = $details;
            $aws_storage_method->save();

            // Update .env file
            setEnvironmentValue([
                'TWILIO_PHONE_NUMBER' => $details['phone_number'],
                'MY_VERIFIED_PHONE_NUMBER' => $details['verified_phone_number'],
                'TWILIO_SID' => $details['sid'],
                'TWILIO_AUTH_TOKEN' => $details['auth_token'],
                'TWILIO_API_KEY_SID' => $details['api_key_sid'],
                'TWILIO_API_KEY_SECRET' => $details['api_key_secret'],
                'TWILIO_TWIML_APP_SID' => $details['twiml_app_sid'],
            ]);

            notify()->success('Voice Call settings updated successfully!');

        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
