<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function() {
    Http::post('https://api.telegram.org/bot' . config('telegram.bot_token') . '/sendMessage', [
        'chat_id' => config('telegram.admin_chat'),
        'text' => 'Reload'
    ]);
    return view('welcome');
});

Route::group(['namespace' => 'App\Http\Controllers'], function(){
    Route::post('/webhook', 'WebhookController@response');
});
