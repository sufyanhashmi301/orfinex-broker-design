<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use App\Models\User;
use App\Enums\KYCStatus;
use App\Services\Kyc\VeriffClient;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VeriffController extends Controller
{
    /**
     * Start Veriff KYC verification process
     */
    public function advanceKyc()
    {
        $plugin = Plugin::where('name', 'Veriff (Automated KYC)')->first();
        // dd($plugin);
        
        if (!$plugin || $plugin->status !== 1) {
            return redirect()->back()->with('error', 'Veriff KYC system not available.');
        }

        $credentials = json_decode($plugin->data);
        $levelName = $credentials->level_name ?? null;
        $user = Auth::user();
        $now = Carbon::now();

        // Check if user already completed Level 2
        if ($user->kyc >= KYCStatus::Level2->value) {
            return redirect()->route('user.kyc')->with('info', 'KYC verification already completed.');
        }

        // Check if user completed Level 1
        if ($user->kyc < KYCStatus::Level1->value) {
            return redirect()->route('user.kyc')->with('error', 'Please complete Level 1 verification first.');
        }

        // Prepare user data for Veriff SDK
        $endUserId = $user->uuid ?? 'user-' . $user->id;
        
        // Update user provider info (no session creation on server-side)
        // TODO: Uncomment after running migration for kyc_provider and kyc_session_id columns
        // $user->kyc_provider = 'veriff';
        // $user->external_kyc_id = $endUserId;
        // $user->kyc_created_at = $now;
        // $user->save();

        Log::info('Veriff KYC initiated via JavaScript SDK', [
            'user_id' => $user->id,
            'end_user_id' => $endUserId,
        ]);

        return view('frontend::user.kyc.veriff.index', [
            'veriffstatus' => $plugin->status,
            'credentials' => $credentials,
            'user' => $user,
            'endUserId' => $endUserId,
            'levelName' => $levelName,
        ]);
    }

    /**
     * Update KYC status (called from frontend after verification)
     */
    public function updateKycStatus(Request $request)
    {
        try {
            $user = Auth::user();
            $action = $request->input('action');
            $status = $request->input('status');
            $sessionId = $request->input('session_id');
            $endUserId = $request->input('end_user_id');
            
            // Handle session ID update
            if ($action === 'update_session_id' && $sessionId) {
                // TODO: Uncomment after running migration for kyc_session_id column
                // $user->kyc_session_id = $sessionId;
                // $user->save();
                
                Log::info('Veriff session ID updated', [
                    'user_id' => $user->id,
                    'session_id' => $sessionId,
                ]);
                
                return response()->json([
                    'status' => 200,
                    'message' => 'Session ID updated successfully'
                ]);
            }
            
            // Handle status updates from frontend
            Log::info('Veriff frontend status update', [
                'user_id' => $user->id,
                'status' => $status,
                // 'session_id' => $user->kyc_session_id, // TODO: Uncomment after migration
                'end_user_id' => $endUserId,
            ]);

            $message = '';
            switch ($status) {
                case 'finished':
                    $message = __('Verification completed! You will be notified once review is complete.');
                    break;
                case 'canceled':
                    $message = __('Verification was canceled. You can try again anytime.');
                    break;
                case 'error':
                    $message = __('Verification encountered an error. Please try again.');
                    break;
                default:
                    $message = __('Verification status updated.');
            }

            return response()->json([
                'status' => 200,
                'message' => $message,
                'data' => [
                    'user_kyc' => $user->kyc,
                    // 'session_id' => $user->kyc_session_id // TODO: Uncomment after migration
                ]
            ]);
            
        } catch (\Throwable $th) {
            Log::error('Veriff status update failed', [
                'user_id' => Auth::id(),
                'error' => $th->getMessage(),
            ]);
            
            return response()->json([
                'status' => 500, 
                'error' => __('Something went wrong.')
            ]);
        }
    }
}
