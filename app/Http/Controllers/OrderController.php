<?php

// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\VerificationCode;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

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

        // Ambil timestamp saat ini
        $timestamp = now();

        $totalPrice = 0;
        $orderData = [];

        // Logika penyimpanan pesanan
        foreach ($basket as $item) {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'menu_name' => $item['menu_name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'created_at' => $timestamp
            ]);
            $totalPrice += $item['price'] * $item['quantity'];
            $orderData[] = $order->toArray();
        }

        // Hitung poin yang akan ditambahkan
        $pointsToAdd = intdiv($totalPrice, 10000);

        // Tambahkan poin ke pengguna
        $user = $request->user();
        $user->addPoints($pointsToAdd);

        // Simpan data pesanan dan poin yang bertambah ke session
        Session::put('recent_order', $orderData);
        Session::put('points_added', $pointsToAdd);

        // Kosongkan keranjang setelah membuat pesanan
        Session::forget('basket');

        // Redirect ke halaman input kode
        return response()->json([
            'success' => true, 
            'redirect' => route('order.inputKode')
        ], 200);
    }

    public function inputKode()
    {
        return view('inputKode');
    }

    public function verifyCode(Request $request)
    {
        $today = Carbon::today();
        $validCode = VerificationCode::where('date', $today)->value('code');

        if ($request->code === $validCode) {
            // Ambil poin yang ditambahkan dari session
            $pointsAdded = Session::get('points_added', 0);
            // Redirect ke halaman success dengan poin yang bertambah
            return redirect()->route('order.success', ['pointsAdded' => $pointsAdded]);
        }

        return back()->withErrors(['code' => 'Invalid code']);
    }

    public function generateCode()
    {
        $today = Carbon::today();

        $verificationCode = VerificationCode::firstOrCreate(
            ['date' => $today],
            ['code' => str_pad(rand(0, 999999), 6 ,'0', STR_PAD_LEFT)]
        );

        return $verificationCode->code;
    }

    public function showCode()
    {
        $today = Carbon::today();
        $code = VerificationCode::where('date', $today)->value('code');

        if (!$code) {
            $code = $this->generateCode();
        }

        return view('show-code', ['code' => $code, 'banner' => 'KODE HARI INI']);
    }

    public function showSuccessPage(Request $request)
    {
        // Ambil timestamp pesanan terbaru
        $latestOrderTimestamp = Order::where('user_id', auth()->id())->max('created_at');

        // Konversi timestamp menjadi objek carbon
        $latestOrderTimestamp = Carbon::parse($latestOrderTimestamp);

        // Ambil semua pesanan dengan timestamp yang sama
        $orders = Order::where('user_id', auth()->id())
                        ->where('created_at', $latestOrderTimestamp)
                        ->get();

        // Hitung total harga
        $totalPrice = $orders->sum(function($order){
            return $order->quantity * $order->price;
        });

        // Ambil poin yang ditambahkan dari parameter
        $pointsAdded = $request->get('pointsAdded', 0);

        return view('purchased-successfull', compact('orders', 'totalPrice', 'latestOrderTimestamp', 'pointsAdded'));
    }
}
