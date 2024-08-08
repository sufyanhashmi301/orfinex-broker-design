<?php
namespace App\Http\Controllers;

use App\Models\ForexAccount;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Http\Request;
use App\Models\Account;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        $update = Telegram::commandsHandler(true);
        $message = $update->getMessage();

        if ($message) {
            $chatId = $message->getChat()->getId();
            $text = $message->getText();

            // Assume the account number is sent as a plain text message
            $accountNumber = $text;

            // Fetch account details from the database
            $account = ForexAccount::where('login', $accountNumber)->first();

            if ($account) {
                $responseText = "Account Details:\n";
                $responseText .= "Balance: {$account->balance}\n";
                $responseText .= "Credit: {$account->credit}\n";
                $responseText .= "Equity: {$account->equity}";

                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                ]);
            } else {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Account not found.',
                ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}

