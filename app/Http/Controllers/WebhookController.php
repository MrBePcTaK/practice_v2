<?php

namespace App\Http\Controllers;

use App\Helpers\Menu;
use App\Helpers\Telegram;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected Telegram $telegram;
    protected Menu $menu;

    public function __construct(Telegram $telegram, Menu $menu)
    {
        $this->telegram = $telegram;
        $this->menu = $menu;
    }

    public function response(Request $request)
    {
        Log::debug($request);
        if(array_key_exists('text', $request->input('message'))) {
            $text = $request->input('message')['text'];
            $author_id = $request->input('message')['from']['id'];
            $permissions = User::query()->where('tg_id', $author_id)->value('permissions');
            Log::debug($author_id . ' ' . $permissions . ' ' . $text);

            if($text == 'ping') {
                $this->telegram->sendMessage($author_id, 'pong');
            } elseif(is_null($permissions)) {
                $this->telegram->sendMessage($author_id, 'permission denied');
            } elseif(str_contains($text, 'Салаты')) {
                $menuArr = $this->menu->parseMenu($text);
                $status = $this->menu->insertMenu($menuArr);
                if($status) {
                    $this->telegram->sendMessage($author_id, 'parsed');
                } else {
                    $this->telegram->sendMessage($author_id, 'error');
                }
            } else {
                $this->telegram->sendMessage($author_id, $text);
            }
        } else {
            Log::debug('empty message');
        }
    }
}
