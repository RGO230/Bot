<?php

namespace App\Console\Commands;

use App\Http\Controllers\BotController;
use App\Models\Tguser;
use Illuminate\Console\Command;

class SendNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendnotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tg_users = TgUser::all();
        foreach ($tg_users as $tg_user) {
            $this->tgSend($tg_user->user_id, "всем привет");
        }
    }
}
    