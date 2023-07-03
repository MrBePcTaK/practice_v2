<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Http;

class Telegram {

    protected Http $http;
    const url = 'https://api.telegram.org/bot';
    const token = '1637322871:AAFg2qbCAPP-sTDBnm027YHQ3obJi-5MN5c';

    public function __construct(Http $http)
    {
        $this->http = $http;
    }

    public function sendMessage($chat_id, $text): void
    {
        $this->http::post(self::url . self::token . '/sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
        ]);
    }

    public function editMessageText($chat_id, $message_id, $text): void
    {
        $this->http::post(self::url . self::token . '/editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => $text,
        ]);
    }

    public function parseMenu($text): array
    {
        $filterStr = str_replace("▪️", '', $text);
        $cats = explode("\n\n", $filterStr);
        $menu = [];
        foreach ($cats as $cat){
            $items = explode("\n", $cat);
            $startWeight = strpos($items[0], '(');
            $endWeight = strpos($items[0], ')');
            $catName = mb_strcut($items[0], 0, $startWeight);
            $catWeight = mb_strcut($items[0], $startWeight + 1, $endWeight - $startWeight-1);
            $catPrice = intval(mb_strcut($items[0], $endWeight + 2));

            $itemArray = [];
            for ($i = 1; $i < count($items); $i++) {
                if(str_contains($items[$i], '-')){
                    if (str_contains($items[$i], '(')) {
                        $name = mb_strcut(
                            $items[$i],
                            0,
                            strpos($items[$i], '(')
                        );
                        $ingridients = mb_strcut(
                            $items[$i],
                            strpos($items[$i], '(') + 1,
                            strpos($items[$i], ')') - strpos($items[$i], '(') - 1
                        );
                    } else {
                        $name = mb_strcut(
                            $items[$i],
                            0,
                            strpos($items[$i], '-')
                        );
                        $ingridients = '';
                    }
                    $price = intval(mb_strcut($items[$i],strpos($items[$i], '-') + 1));
                } else {
                    $name = $items[$i];
                    $ingridients = '';
                    $price = $catPrice;
                }
                $itemArray[] = (object) array(
                    'name' => $name,
                    'ingridients' => $ingridients,
                    'price' => $price,
                );
            }

            $menu[] = (object) array(
                'name' => $catName,
                'weight' => $catWeight,
                'items' => $itemArray,
            );
        }

        return $menu;
    }

    public function insertMenu($menu): bool
    {
        foreach ($menu as $cat){
            foreach ($cat->{'items'} as $item) {
                $product = new Product;

                $product->name = $item->{'name'};
                if ($item->{'ingridients'} == '') {
                    $product->ingridients = null;
                } else {
                    $product->ingridients = $item->{'ingridients'};
                }
                $product->weight = $cat->{'weight'};
                $product->price = $item->{'price'};
                $product->date = now();

                $product->save();
            }
        }
        return true;
    }
}
