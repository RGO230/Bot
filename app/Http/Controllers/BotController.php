<?php

namespace App\Http\Controllers;

use App\Models\Tguser;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Telegram\Bot\Laravel\Facades\Telegram;

class BotController extends Controller
{

    public function update()
    {
        $updates = Telegram::getWebhookUpdates();
        return (json_encode($updates));
    }

    public function tgSend($chat_id, $mes)
    {
        $path = "https://api.telegram.org/bot" . env("TELEGRAM_BOT_TOKEN");
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, $path . "/sendmessage?chat_id=" . $chat_id . "&text=" . urlencode($mes) . "");
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
        $jsonData = json_decode(curl_exec($curlSession));
        curl_close($curlSession);
    }

    public function sendMassage()
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        
        $message = $data['message']['text'];
        $chat_id = $data['message']['chat']['id'];
        $username = $data['message']['from']['username'];

        
        $user = Tguser::firstOrCreate(['user_id' => $chat_id, 'username' => $username], ['user_id' => $chat_id, 'username' => $username]);

        if (strpos($message, "/txt") !== false) {
            if ($user->counter <= 20 || ($user->counter > 20 && $user->isvip)) {
                Tguser::where('user_id', $chat_id)->update(['counter' => $user->counter + 1]);
            } else {
                $this->tgSend($chat_id, "Only 20 requests are available for free");
                return;
            }
            $words = explode(' ', $message);
            $words = array_diff($words, ['/txt']);
            $words = implode(" ", $words);


            $result = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => $words,
            ]);
            $result = $result['choices'][0]['text'];
   
            $this->tgSend($chat_id, $result);
            
        } elseif(strpos($message,"/start") !== false) {
            $this->tgSend($chat_id, 'To make a request, write it after /txt');
        }  else {
            $this->tgSend($chat_id, 'To make a request, write it after /txt');
        }
    

        return;
    }
}