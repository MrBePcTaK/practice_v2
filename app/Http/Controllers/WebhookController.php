<?php

namespace App\Http\Controllers;

use App\Helpers\Telegram;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Container\Container;

class WebhookController extends Controller
{
    protected Telegram $telegram;

    public function __construct(Container $container, Telegram $telegram)
    {
        # protected Container $container;
        $this->telegram = $telegram;
    }

    public function resp(Request $request, Telegram $telegram)
    {
        Log::debug($request);
        $text = $request->input('message')['text'];
        $author_id = $request->input('message')['from']['id'];
        $permissions = User::query()->where('tg_id', $author_id)->value("permissions");
        Log::debug($author_id . ' ' . $permissions . ' ' . $text);

        if($text == 'ping'){
            $this->telegram->sendMessage($author_id, 'pong');
        } elseif($permissions == NULL){
            $this->telegram->sendMessage($author_id, 'permission denied');
        } elseif(str_contains($text, 'Салаты')){
            $menu = $this->telegram->parseMenu($text);
            $status = $this->telegram->insertMenu($menu);
            if($status) {
                $this->telegram->sendMessage($author_id, 'parsed');
            } else {
                $this->telegram->sendMessage($author_id, 'error');
            }
        } else {
            $this->telegram->sendMessage($author_id, $text);
        }
    }
}
