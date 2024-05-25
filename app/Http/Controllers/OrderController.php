<?php

namespace App\Http\Controllers;

use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    private $apiUrl;
    private $token;

    public function __construct()
    {
        $this->apiUrl = env('API_URL');
        $this->token = env('API_TOKEN');
    }

    public function index()
    {
        return view('order', [
            'banner' => 'PEMBELIAN'
        ]);
    }

    public function pembelian()
    {
        $client = new Client();
        $groupedData = [];

        try {
            $response = $client->get($this->apiUrl . '/menu', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] == 'Success') {
                usort($data['data'], function ($a, $b) {
                    return $a['category_id'] <=> $b['category_id'];
                });

                $groupedData = $this->groupByCategory($data['data']);
            }
        } catch (\Exception $e) {
            // Handle error
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
        $price = $request->input('price');

        $basket = Session::get('basket', []);
        if (isset($basket[$menuItem])) {
            $basket[$menuItem]['quantity'] += $quantity;
        } else {
            $basket[$menuItem] = [
                'menu_name' => $menuItem,
                'quantity' => $quantity,
                'price' => $price,
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

    public function createOrder(Request $request)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'order' => 'required|array',
            'order.*.menu_name' => 'required|string',
            'order.*.quantity' => 'required|integer|min:1',
            'order.*.price' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Ambil item dari session atau request
        $basket = $request->input('order', []);

        // Logika penyimpanan pesanan
        foreach ($basket as $item) {
            Order::create([
                'user_id' => $request->user()->id,
                'menu_name' => $item['menu_name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Kosongkan keranjang setelah membuat pesanan
        Session::forget('basket');

        // Redirect ke halaman success
        return response()->json(['success' => true, 'redirect' => route('order.success')], 200);
    }

    public function showSuccessPage()
    {
        return view('purchased-successfull');
    }

    public function inputKode(){
        return view('inputKode');
    }
}
