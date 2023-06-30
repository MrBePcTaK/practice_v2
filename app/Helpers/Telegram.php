<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Telegram {
    
    protected $http;
    const url = 'https://api.telegram.org/bot';
    const token = '1637322871:AAFg2qbCAPP-sTDBnm027YHQ3obJi-5MN5c';

    public function __construct(Http $http)
    {
        $this->http = $http;
    }
    
    
    public function sendMessage($chat_id, $message){
        $this->http::post(self::url . self::token . '/sendMessage', [
            'chat_id' => $chat_id,
            'text' => $message,
        ]);
    }
}