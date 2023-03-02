<?php

namespace App\Http\Controllers;

use App\Models\Tguser;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Telegram\Bot\Laravel\Facades\Telegram;

class BotController extends Controller
{

    public function update(){
        $updates = Telegram::getWebhookUpdates();
        return (json_encode($updates));
    }
    public function sendMassage(){
        $data = file_get_contents('php://input');
        $data = json_decode($data,true);
        $message = $data['text'];
        $chat_id = $data['message']['chat']['id'];
        $username=$data['message']['from']['username'];
        if(strpos($message,"/txt") !== false) {
            $words=explode(' ',$message);
            $words = array_diff($words,['/txt']);
            $words=implode(" ",$words);

             
        $result = OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $words,
        ]);
    }
        $user=Tguser::firstOrCreate(['user_id'=>$chat_id,'username'=>$username],['user_id'=>$chat_id,'username'=>$username]);
        if($user->counter<=20||($user->counter>20&&$user->isvip==1)){
            Tguser::where('user_id',$chat_id)->update(['counter'=>$user->counter++,]);
        }
        else{
            Tguser::where('user_id',$chat_id)->update(['isvip'=>$user->isvip=0]);
        }
        $result=$result['choices'][0]['text'];
        if($user->isvip==0){
        Telegram::sendMassage([
            'chat_id' => $chat_id,
            'text' => "Only 20 requests are available for free",
        ]);
    }
        else{
            Telegram::sendMassage([
                'chat_id' => $chat_id,
                'text' => $result,
            ]);
        }
        return;
    }       
}
