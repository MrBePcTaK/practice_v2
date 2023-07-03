<?php

use App\Models\Product;
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
    Http::post('https://api.telegram.org/bot1637322871:AAFg2qbCAPP-sTDBnm027YHQ3obJi-5MN5c/sendMessage', [
        'chat_id' => '681625605',
        'text' => 'Reload'
    ]);
    return view('welcome');
});

Route::group(['namespace' => 'App\Http\Controllers'], function(){
    Route::post('/webhook', 'WebhookController@resp');
});
