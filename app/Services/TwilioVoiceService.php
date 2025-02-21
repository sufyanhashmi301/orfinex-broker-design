<?php

namespace App\Services;

use Twilio\Exceptions\RestException;
use Twilio\Rest\Client;
use Twilio\TwiML\VoiceResponse;

class TwilioVoiceService
{
    public function __construct()
    {
           // Twilio credentials
        $this->account_sid = env('TWILIO_SID');
        $this->auth_token = env('TWILIO_AUTH_TOKEN');
        //the twilio number you purchased
        $this->from = env('TWILIO_PHONE_NUMBER');

        // Initialize the Programmable Voice API
        $this->client = new Client($this->account_sid, $this->auth_token);
    }



    public function initiateCallsss($phoneNumber)
    {
        $response = new VoiceResponse();

        dd($phoneNumber, $this->from);
        // Initiate an outgoing call
        $client = new Client($this->account_sid, $this->auth_token);
        $call = $client->calls->create(
            $this->from, // Caller's number
            $phoneNumber,   // Your Twilio number
            [
                'url' => route('outgoing-call'), // URL to handle the outgoing call
                'method' => 'POST',
            ]
        );

        $dial = $response->dial();
        $dial->conference('MyConference'); // Connect the incoming call to a conference

        return response($response)
            ->header('Content-Type', 'text/xml');
    }

    public function handleOutgoingCall(Request $request)
    {
        $response = new VoiceResponse();

        $response->say('Connecting your call...');

        return response($response)
            ->header('Content-Type', 'text/xml');
    }




    public function initiateCall($phoneNumber)
    {
        try {
            // Create a new Twilio VoiceResponse
            $response = new VoiceResponse();
            
            $phone_number = $this->client->lookups->v1->phoneNumbers($phoneNumber)->fetch();
           
            if ($phone_number) {
                $call = $this->client->calls->create($phoneNumber, $this->from,[
                    "twiml" => "<Response><Dial><Number>$phoneNumber</Number></Dial></Response>"
                    ]); 
                

                // If call was successfully initiated
                if ($call) {
                    return $call->sid; // Return the call SID
                }
    
                return null; // Return null if call creation failed
            }
        } catch (Exception $e) {
            dd($e);
            return null;
        } catch (RestException $rest) {
            dd($rest);
            return null;
        }
    }
    

    public function getCallDetails($callSid)
    {
        try {
            $call = $this->client->calls($callSid)->fetch();
            
            $callDetails = $call->toArray();

            // Fetch the recordings associated with the call
            $recordings = $this->client->recordings->read([
                'callSid' => $callSid,
            ]);

            // Prepare recording URLs
            $recordingUrls = [];
            foreach ($recordings as $recording) {
                $recordingUrls[] = [
                    'url' => route('admin.recording.stream', ['recordingSid' => $recording->sid]), // Proxy URL
                    'sid' => $recording->sid,
                ];
            }
    
            return [
                'success' => true,
                'callDetails' => $callDetails,
                'recordings' => $recordingUrls, // Include the proxy URLs in the response
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error occurred while fetching call details and recordings.',
            ];
        }
    }


    public function streamRecording($recordingSid)
    {
        try {
            // Construct the Twilio recording URL
            $recordingUrl = "https://api.twilio.com/2010-04-01/Accounts/" . env('TWILIO_SID') . "/Recordings/{$recordingSid}.mp3";

            // Make a GET request to Twilio's API
            return $this->client->request('GET', $recordingUrl);

        } catch (\Exception $e) {
            return response('Unable to fetch recording.', 404);
        }
    }
    

    public function endCall($callSid)
    {
        try {
            $this->client->account->calls($callSid)->update(['status' => 'completed']);
            return true; // Return true on success
        } catch (\Exception $e) {
            return false; // Return false on failure
        }
    }

    public function muteMicrophone($callSid)
    {
        try {
            $this->client->account->calls($callSid)->update(['mute' => 'true']);
            return true; // Return true on success
        } catch (\Exception $e) {
            return false; // Return false on failure
        }
    }

    public function getCallHistory($startOfWeek, $endOfWeek)
    {
        // Fetch call logs
        $calls = $this->client->calls->read([
            'StartTimeAfter' => $startOfWeek,
            'Status' => 'completed',
            'StartTimeBefore' => $endOfWeek,
        ]); // Fetch last 20 calls

        return $calls;
    }
}