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


    public function notif()
    {
        $tg_users = TgUser::all();

        foreach ($tg_users as $tg_user) {
            $this->tgSend($tg_user->user_id, "всем привет");
        }

        return;
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

        if (!isset($data['message']['text'])) {
            return;
        }

        $message = $data['message']['text'];
        $chat_id = $data['message']['chat']['id'];
        $tg_user_id = $data['message']['from']['id'];
        $username = $data['message']['from']['username'];


        $user = Tguser::firstOrCreate(['user_id' => $tg_user_id, 'username' => $username]);


        if (strpos($message, "/txt") !== false) {

            if ($user->counter <= 20 || ($user->counter > 20 && $user->isvip == 1)) {
                Tguser::where('user_id', $chat_id)->update(['counter' => $user->counter + 1]);
            } else {
                $this->tgSend($chat_id, "
                You have ran out of free requests. You can use the bot without limitations after subscription. To subscribe dm our manager @brainsmanager
                ");
                return;
            }
          
            $message = $this->checkBadWords($message, $chat_id);

            if(!$message) {
                return;
            }
            
            $result = $this->getTextChatGpt($message);

            $this->tgSend($chat_id, $result);

        } elseif (strpos($message, "/start") !== false) {
            $this->tgSend($chat_id, "
            Introducing our Telegram bot powered by OpenAI's language model API! 

            With our bot, users can generate natural language responses to a variety of prompts, from creative writing prompts to conversation starters.

            To start using the bot, first, follow our channel (https://t.me/+-1rkHafDK0syNzcy), where we regularly post info on useful AI tools for money making. Second, read the rules of this bot by typing /rules in this chat. If you don't comply with our rules you will be banned from using the bot.

            To begin, use /txt and then type your request. Good luck!
                        ");
        } elseif (strpos($message, "/rules") !== false) {
            $this->tgSend($chat_id, "
                        To ensure a safe and respectful environment for all users, we ask that you adhere to the following rules:

            Do not use offensive language in your requests or responses. We do not tolerate hate speech or discriminatory language of any kind.

            Do not abuse the language model. Excessive requests or attempts to overload the model will not be tolerated.

            Avoid discussing controversial topics such as race, politics, religion, gender, and other sensitive topics. Our bot is intended to be a fun and helpful tool, not a platform for divisive or harmful conversations.

            You have 20 free requests. Use them wisely. After they run out you can keep using the bot by paying a small fee of $3. This works as a monthly subscription. Every month your free requests will be renewed. To subscribe please dm @brainsmanager for instructions and payment confirmation. After you pay you will be able to use the bot as much as you want. Or use /subscription command.

            By using our Telegram bot, you agree to these rules and understand that violating them may result in your access being revoked. We hope you enjoy using our bot and look forward to seeing what kind of creative responses you can generate!
            ");
        } elseif (strpos($message, "/subcription") !== false) {
            $this->tgSend($chat_id, "
            In order to pay for your subscription - dm our manager @brainsmanager 👈. You will receive instructions on how to pay and then will be added to the VIP users list. The price is $3/month for unlimited use.
            ");
        } else {
            $this->tgSend($chat_id, 'To make a request, write it after /txt');
        }


        return;
    }



    private function getTextChatGpt($words)
    {
        $ch = curl_init();

        $url = 'https://api.openai.com/v1/chat/completions';

        $api_key = "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";


        $post_fields = array(
            "model" => "gpt-3.5-turbo",
            "messages" => array(
                array(
                    "role" => "user",
                    "content" => $words
                )
            ),
            "max_tokens" => 3000,
            "temperature" => 0
        );

        $header  = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }
        curl_close($ch);

        $result = json_decode($result, true);
        $result = $result['choices'][0]['message']['content'];

        return $result;
    }


    private function checkBadWords($message, $chat_id)
    {
        $arr = file('bad_words.txt');
        $bad_words = [];

        foreach ($arr as $w) {
            $bad_words[] = trim($w, "\n");
        }
        
        $words = explode(' ', $message);
        foreach ($words as $w) {
            if (in_array($w, $bad_words)) {
                $this->tgSend($chat_id, "Sorry. Can't answer this");
                return false;
            }
        }

        $words = array_diff($words, ['/txt']);
        $words = implode(" ", $words);
        return $words;
    }
    
    // private function checkToken($chat_id) {
    //     $context = Context::all();
    //     $words = [];
    //     foreach($context as $c) {
    //         $words[] = explode(' ', $c->content);
    //     }
        
    //     if(count($words) >= 4096) {
    //         return false;
    //     }
        
    //     return true;
    // }
}
?>