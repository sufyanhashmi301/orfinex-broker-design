<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\CallHistory;
use App\Services\TwilioVoiceService;

use Twilio\TwiML\VoiceResponse;
use Exception;
use Illuminate\Support\Facades\Log;

use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;
use Carbon\Carbon;

class TwilioController extends Controller
{

    protected $twilioVoiceService;

    public function __construct(TwilioVoiceService $twilioVoiceService)
    {
        $this->twilioVoiceService = $twilioVoiceService;
    }


    public function index(Request $request)
    {

        return view('backend.twilio.index');
    }


    public function voice()
    {
        $data['customerPhoneNumber'] = '+8801752474197';
        $data['customerName'] = 'Saiful';
        return view('iframe.meeting', $data);
    }

    public function outboundXml(Request $request)
    {


        $jsonData = request()->all();
        $defaultCountryCode =  env('COUNTRY_CODE');
        $phoneNumber = $jsonData['To'] ?? env('TWILIO_PHONE_NUMBER');
        $direction = 'Outgoing';

        $CallSid = $jsonData['CallSid'];
        $Caller = $jsonData['Caller'];


        $response = new VoiceResponse();

        if ($phoneNumber == env('TWILIO_PHONE_NUMBER')) {
            // Incoming call handle 
            $direction = 'Incoming';
            $response->dial()->client('test_client');
        } else {
            // Outgoing call handle
            $from = env('TWILIO_PHONE_NUMBER');
            $response->dial($phoneNumber, [
                'callerId' => $from,
                'record' => 'true'
            ]);
        }

        Log::info('Twilio ' . $direction . ' call request received.', [
            'request' => $jsonData,
            'to' => $phoneNumber
        ]);

        return response($response, 200)->header('Content-Type', 'text/xml');
    }

    public function handleIncomingCall(Request $request)
    {

        Log::info('Twilio incoming call.', [
            'request' => request()->all(),
        ]);

        $response = new VoiceResponse();

        // Play a message or connect to Twilio Client
        $response->play('https://fixfinanz.de/assets/audio/office_phone.mp3');
        $response->dial()->client('support_agent'); // Replace 'support_agent' with your client name

        return response($response, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function handleOutgoingCall(Request $request)
    {

        Log::info('Twilio outbound call request received.', [
            'request' => request()->all(),
        ]);

        $response = new VoiceResponse();
        $response->dial($request->input('To'), ['callerId' => env('TWILIO_PHONE_NUMBER')]);  // Optionally set a caller ID TWILIO_PHONE_NUMBER

        return response($response, 200)->header('Content-Type', 'text/xml');

        // $response = new VoiceResponse();

        // $response->say('Hello, thank you for calling.');
        // $dial = $response->dial();
        // $dial->number($request->input('To'));

        // \Log::info('To Number: ' . $request->input('To'));

        // return response($response)
        //     ->header('Content-Type', 'text/xml');


        // $response = new Response();
        // $response->header('Content-Type', 'text/xml');

        // // Example TwiML to connect the caller to the recipient
        // $response->setContent('
        //     <Response>
        //         <Dial>
        //             <Number>'.$request->input('To').'</Number> </Dial>
        //     </Response>
        // ');

    }

    public function initiateCall(Request $request)
    {
        // Validate form input
        $this->validate($request, [
            'phone_number' => 'required|string',
        ]);

        // Call the Twilio Voice service to initiate the call
        // $phone_number = env('COUNTRY_CODE').''.$request->phone_number;
        // $call_sid = $this->twilioVoiceService->initiateCall($request->phone_number);

        return response()->json([
            'success' => true,
            'call_sid' => 'ok',
            'message' => 'Call connected succssfully!'
        ]);

        if ($call_sid) {
            return response()->json([
                'success' => true,
                'call_sid' => $call_sid,
                'message' => 'Call connected succssfully!'
            ]);
        }
        return response()->json([
            'success' => false,
            'call_sid' => null,
            'message' => 'Sorry! Call cannot connected!'
        ]);
    }

    public function endCall(Request $request)
    {
        // Validate form input
        $this->validate($request, [
            'call_sid' => 'required|string',
        ]);

        // Call the Twilio Voice service to end the call
        $result = $this->twilioVoiceService->endCall($request->call_sid);

        return response()->json(['result' => $result]);
    }

    public function muteMicrophone(Request $request)
    {
        // Validate form input
        $this->validate($request, [
            'call_sid' => 'required|string',
        ]);

        // Call the Twilio Voice service to mute the microphone
        $result = $this->twilioVoiceService->muteMicrophone($request->call_sid);

        return response()->json(['result' => $result]);
    }

    public function playCallRecording($callSid)
    {

        $response = $this->twilioVoiceService->getCallDetails($callSid);
        if ($response['success']) {
            $callDetails = $response['callDetails'];

            $recordings = $response['recordings'];

            // Pass call details and recordings to the Blade view
            return view('admin.twilio.audio', compact('callDetails', 'recordings'));
        } else {
            return view('admin.twilio.audio', ['error' => $response['message']]);
        }
    }

    public function streamRecording($recordingSid)
    {
        try {
            // Fetch the recording from Twilio API
            $recordingUrl = "https://api.twilio.com/2010-04-01/Accounts/" . env('TWILIO_SID') . "/Recordings/{$recordingSid}.mp3";

            // Make a direct HTTP request to fetch the recording
            $client = new \GuzzleHttp\Client();
            $response = $client->get($recordingUrl, [
                'auth' => [env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN')],
            ]);

            // Return the audio content as a streamable response
            return response($response->getBody(), 200)
                ->header('Content-Type', $response->getHeader('Content-Type')[0]);
        } catch (\Exception $e) {
            return response('Unable to fetch recording.', 404);
        }
    }

    public function playerIframe($link)
    {
        $link = request()->link;

        $recordingSid = $recordingSid = basename($link);
        $response = $this->twilioVoiceService->getRecordingDetails($recordingSid);


        $duration = 0;
        if ($response['success']) {
            $duration = $duration = gmdate("i:s", $response['duration']);
        }

        return view('admin.twilio.iframe', compact('link', 'duration'));
    }

    public function insertCallHistory(Request $request)
    {
        try {

            $response = $this->twilioVoiceService->getCallDetails($request->call_sid);

            // $contact = $this->firstOrCreate($call_history['callDetails']['to'], $request->status);
            $number  = $request->toNumber;

            if ($response['success'] && !empty($number)) {

                $call_history = $response['callDetails'];
                $recordings = $response['recordings'];

                // Get the first recording URL, if available
                $recording_link = $recordings[0]['url'] ?? null;

                CallHistory::create([
                    'contact_id' => 0,
                    'user_id' => auth()->user()->id,
                    'call_sid' => $call_history['sid'],
                    'direction' => $call_history['direction'],
                    'from' => substr($call_history['from'], 7), // Remove the 'client:' prefix from the 'from' number
                    'to' => $number,
                    'type' => 'voice',
                    'duration' => $call_history['duration'],
                    'status' => $call_history['status'],
                    'recordings' => $recording_link,
                ]);


            }

            return response()->json(['result' => 'Call history inserted!']);
        } catch (Exception $e) {

            return response()->json(['result' => 'Something went wrong!']);
        }
    }


    // public function insertDealHistory($deal_id, $recording_link, $duration, $number)
    // {

    //     $userContact = UserContact::where('deal_id', $deal_id)->first();
    //     if ($userContact) {
    //         $message =  '<div class="d-flex">';
    //         $message .= '<p class="col-md-5"><strong>Erreicht :</strong> <span class="badge px-3 bg-success">Called</span></p>';
    //         if ($recording_link) {
    //             // $message .= '<div class="cal-md-7><audio controls><source src="' . $recording_link . '" type="audio/mpeg">Your browser does not support the audio element.</audio></div>';
    //             $message .= '<div class="cal-md-7"><iframe src="' . url("/player-iframe/{$deal_id}?link={$recording_link}") . '" style="height:92px;width:340px;" title="Iframe Example"></iframe></div>';
    //         }
    //         $message .= '</div>';
    //         $message .= '<p><strong>Telefonisch :</strong> ' . $number . '</p> <p> Duration: ' . $duration . '</p>';

    //         DealHistory::create([
    //             'deal_id' => $deal_id,
    //             'message' => $message
    //         ]);
    //     }
    // }


    public function userReport()
    {
        // $user = auth()->user();
        // $ownerId = request()->ownerId ?? '49466414';
        // if($user->role == 1){
        //     $ownerId = $user->h_id;
        // }
        // $startDate = Carbon::now()->startOfWeek();
        // $endDate = Carbon::now()->endOfWeek();



        // $users = $this->hubSpotService->getUsers();

        // $targetSums = $this->hubSpotService->getUserTargetSums($ownerId);
        // $engagementCountsByDayAndType = $this->hubSpotService->getUserEngagement($startDate, $endDate, $ownerId);

        // $typeTranslation = $this->getTypes();


        // return view('admin.report.user', [
        //     'engagementCountsByDayAndType' => $engagementCountsByDayAndType,
        //     'targetSums' => $targetSums,
        //     'owners' => $users,
        //     'author' => $user,
        //     'ownerId' => $ownerId,
        //     'typeTranslation' => $typeTranslation,
        //     'startDate' =>  Carbon::now()->startOfWeek(),
        //     'endDate' => Carbon::now()->endOfWeek(),
        //     'week_dates' => $this->getweekDates($startDate, $endDate)
        // ]);

        $startDate = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endDate = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $users = User::whereIn('role_id', [1, 11])->get();

        return view('admin.report.index', [
            'startDate' =>  $startDate,
            'endDate' => $endDate,
            'users' => $users
        ]);
    }

 
    public function formatAmountToGerman($amount)
    {
        $amount = (float) $amount;
        return number_format($amount, 2, ',', '.');
    }


    public function getWeeklyCallReport(Request $request)
    {
        $callHistories = CallHistory::orderBy('id', 'desc')->get();
    
        $html = '';
    
        foreach ($callHistories as $history) {
            $badgeClass = $this->getBadgeClass($history->status);
            $formattedDuration = $this->formatDuration($history->duration);
            $fullname = 'Unknown'; // Default name
    
            // Example of how you might fetch the contact name (commented out)
            // $contact = UserContact::where('deal_id', $history->contact_id)->first();
            // if ($contact) {
            //     $fullname = $contact->name . ' ' . $contact->last_name;
            // }
    
            // Tailwind CSS classes for the list item
            $html .= '<li class="p-4 border-b border-gray-200 hover:bg-gray-50">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">' . $history->to . '</span>
                            <span class="px-2 py-1 rounded text-sm ' . $badgeClass . '">' . $formattedDuration . '</span>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-sm text-gray-600">' . $history->created_at->format('d.m.Y h:i') . '</span>
                            <div class="mt-3 flex items-center justify-end">';

                            // Show audio player only if there is a recording
                            if (!empty($history->recordings)) {
                                $html .= '<audio controls class="w-64">
                                            <source src="' . $history->recordings . '" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>';
                            }

            $html .= '</div>
                    </div>
                </li>';

        }
    
        // If no call history exists
        if (count($callHistories) <= 0) {
            $html .= '<li class="p-4 text-center text-gray-500">No call history!</li>';
        }
    
        return response()->json(['html' => $html]);
    }
    
    private function getBadgeClass($status)
    {
        switch ($status) {
            case 'no-answer':
                return 'bg-blue-500 text-white'; // Tailwind classes for primary
            case 'canceled':
                return 'bg-red-500 text-white'; // Tailwind classes for danger
            case 'completed':
                return 'bg-black-500 text-white'; // Tailwind classes for success
            default:
                return 'bg-gray-500 text-white'; // Tailwind classes for secondary
        }
    }
    

    private function formatDuration($seconds)
    {
        $minutes = intdiv($seconds % 3600, 60);
        $seconds = $seconds % 60;

        return sprintf("%02d:%02d", $minutes, $seconds);
    }



    public function insertNewCustomer(Request $request)
    {
        // $contact = $this->insertContact($request->number, $request->status);

        // if($contact){
        //     return response()->json([
        //         'success' => true,
        //         'contact_id' => $contact->id,
        //         'message' => 'Conteact created successfully!'
        //     ]);
        // }else{
        //     return response()->json([
        //         'success' => false,
        //         'contact_id' => 0,
        //         'message' => 'something went wrong'
        //     ]);
        // }

        return response()->json([
            'success' => true,
            'contact_id' => 3,
            'message' => 'Conteact created successfully!'
        ]);
    }


    public function insertContact($number, $status)
    {
        $user_id = auth()->user()->id;
        $number = $this->convertCorrectNumber($number);
        $contact = Contact::updateOrCreate(
            [
                'number' => $number // Condition to check if the contact exists
            ],
            [
                'user_id' => $user_id,
                'status' => $status
            ] + (Contact::where('number', $number)->exists() ? [] : ['slug' => rand(10000000, 99999999)])
        );

        return $contact;
    }

    public function firstOrCreate($number, $status)
    {
        $user_id = auth()->user()->id;
        $number = $this->convertCorrectNumber($number);
        $contact = Contact::firstOrCreate(
            [
                'number' => $number
            ],
            [
                'user_id' => $user_id,
                'status' => $status,
                'slug' =>  rand(10000000, 99999999)
            ]
        );

        return $contact;
    }


    public function generateToken()
    {
        $identity = 'test_client'; // Unique identifier for the user/device

        $token = new AccessToken(
            env('TWILIO_SID'),
            env('TWILIO_API_KEY_SID'),
            env('TWILIO_API_KEY_SECRET'),
            3600 // Token validity duration in seconds
        );

        // Add a VoiceGrant
        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid(env('TWILIO_TWIML_APP_SID')); // TwiML App SID for outgoing calls
        $voiceGrant->setIncomingAllow(true); // Allow incoming calls
        $token->addGrant($voiceGrant);

        // Set the identity for the client
        $token->setIdentity($identity);

        return response()->json([
            'token' => $token->toJWT(),
            'identity' => $identity, // Return identity for reference
        ]);
    }



    public function convertCorrectNumber($phoneNumber)
    {
        $countryCode = env('COUNTRY_CODE');
        $pattern = '/^\+490(\d+)/';
        $correctedPhoneNumber = preg_replace($pattern, $countryCode . '$1', $phoneNumber);
        return $correctedPhoneNumber;
    }
}
