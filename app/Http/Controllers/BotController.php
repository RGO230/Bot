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
    public function test()
    {

        $response = Telegram::getMe();
        $botId = $response->getId();
        $firstName = $response->getFirstName();
        $username = $response->getUsername();
        return $botId;
    }
}
