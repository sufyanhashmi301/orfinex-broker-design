<?php
namespace App\Http\Controllers;

use App\Models\ForexAccount;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Http\Request;
use Log;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        Log::info('Webhook received:', $request->all());

        try {
            // Manually process the update
            $update = Telegram::bot('banexcapital_bot')->getWebhookUpdates();
            Log::info('Update processed:', ['update' => $update]);

            $message = $update->getMessage();
            if ($message) {
                $chatId = $message->getChat()->getId();
                $text = $message->getText();

//                Log::info('Message received:', ['chatId' => $chatId, 'text' => $text]);

                // Assume the account number is sent as a plain text message
                $accountNumber = $text;

                // Fetch account details from the database
                $account =  DB::connection('mt5_db')->table('mt5_users')->where('Login', $accountNumber)->first();

                if ($account) {
                    $responseText = "Account Details:\n";
                    $responseText .= "Balance: {$account->Balance}\n";
                    $responseText .= "Credit: {$account->Credit}\n";
                    $responseText .= "Equity: {$account->Equity}";
                    $responseText .= "Used Margin: {$account->Margin}";
                    $responseText .= "Free Margin: {$account->MarginFree}";
                    $responseText .= "Floating: {$account->Floating}";

                    Log::info('Account found:', ['account' => $account]);

                    Telegram::bot('banexcapital_bot')->sendMessage([
                        'chat_id' => $chatId,
                        'text' => $responseText,
                    ]);
                } else {
                    Log::warning('Account not found:', ['accountNumber' => $accountNumber]);

                    Telegram::bot('banexcapital_bot')->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Account not found.',
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error processing webhook:', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error'], 500);
        }

        return response()->json(['status' => 'ok sufyan']);
    }
}
