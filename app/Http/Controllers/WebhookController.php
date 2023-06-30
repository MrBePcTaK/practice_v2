<?php

namespace App\Http\Controllers;


use App\Helpers\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Container\Container;

class WebhookController extends Controller
{
    protected $telegram;

    public function __construct(Container $container, Telegram $telegram)
    {
        # protected Container $container;
        $this->telegram = $telegram;
    }
    
    public function resp(Request $request, Telegram $telegram){
        Log::debug($request->all());
        $text = $request->input('message')['text'];
        $author_id = $request->input('message')['from']['id'];;
        #Log::debug($text);
        #Log::debug($author_id);

        if($text == 'ping'){
            $this->telegram->sendMessage('681625605', 'pong');
        } elseif($author_id != 681625605){
            $this->telegram->sendMessage('681625605', 'permission denied');
        } else {
            $this->telegram->sendMessage('681625605', 'parse');
        }

        
    }

};