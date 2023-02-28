<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Telegram\Bot\Laravel\Facades\Telegram;

class BotController extends Controller
{
    public function create()
    {
        $result = OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => 'хохлы свиньи?',
        ]);

        echo $result['choices'][0]['text'];
    }
    
    public function update(){
        $updates = Telegram::getWebhookUpdates();
        return (json_encode($updates));
    }
    public function sendMassage(Request $request){
        file_put_contents('test.txt',json_encode($request->all()));
        Telegram::sendMassage([
            'chat_id' => 'RECIPIENT_CHAT_ID',
            'text' => 'Привет, мир!'
        ]);
        return;
    }
}
