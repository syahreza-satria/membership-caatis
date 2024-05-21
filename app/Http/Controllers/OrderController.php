<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $apiUrl = 'https://dev-lakeside.matradipti.org/api';
    private $token = '177|QcqnArHgR22GI7dfwhfaGSK1c1wQFE4g25SMWode';

    public function index()
    {
        return view('order', [
            'banner' => 'PEMBELIAN'
        ]);
    }

    public function pembelian()
    {
        $client = new Client();

        // Step 1: Gunakan token untuk mendapatkan data menu
        try {
            $response = $client->get($this->apiUrl . '/menu', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] == 'Success') {
                // Sort the data by 'category_id'
                usort($data['data'], function ($a, $b) {
                    return $a['category_id'] <=> $b['category_id'];
                });

                $groupedData = $this->groupByCategory($data['data']);
            } else {
                $groupedData = [];
            }

        } catch (\Exception $e) {
            // Handle error
            $groupedData = [];
        }

        return view('menu', [
            'banner' => 'MENU',
            'data' => $groupedData
        ]);
    }

    private function groupByCategory($menus)
    {
        $grouped = [];
        foreach ($menus as $menu) {
            $grouped[$menu['category_id']][] = $menu;
        }
        return $grouped;
    }

    public function addToBasket(Request $request)
    {
        $menuItem = $request->input('menu_item');
        $quantity = $request->input('quantity');

        $basket = Session::get('basket', []);
        if (isset($basket[$menuItem])) {
            $basket[$menuItem]['quantity'] += $quantity;
        } else {
            $basket[$menuItem] = [
                'menu_name' => $menuItem,
                'quantity' => $quantity,
                'price' => $request->input('price'),
            ];
        }

        Session::put('basket', $basket);

        return response()->json(['basket' => $basket]);
    }

    public function getBasket()
    {
        $basket = Session::get('basket', []);
        return response()->json(['basket' => $basket]);
    }

    public function removeFromBasket(Request $request)
    {
        $menuItem = $request->input('menu_item');

        $basket = Session::get('basket', []);
        if (isset($basket[$menuItem])) {
            if ($basket[$menuItem]['quantity'] > 1) {
                $basket[$menuItem]['quantity'] -= 1;
            } else {
                unset($basket[$menuItem]);
            }
        }

        Session::put('basket', $basket);

        return response()->json(['basket' => $basket]);
    }
}
