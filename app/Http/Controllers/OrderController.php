<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Branch;
use GuzzleHttp\Client;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    private $apiUrl;
    private $token;

    public function __construct()
    {
        $this->apiUrl = env('API_URL');
        $this->token = env('API_TOKEN');
    }

    public function showBranch()
    {
        $branches = Branch::all();

        return view('orders.branches', [
            'banner' => 'PEMBELIAN',
            'branches' => $branches
        ]);
    }

    public function pembelian()
    {
        $client = new Client();
        $groupedData = [];
        $categories = $this->fetchCategories();

        try {
            $response = $client->get($this->apiUrl . '/menu', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] == 'Success') {
                // Tambahkan kategori dan id ke setiap item menu
                foreach ($data['data'] as &$menu) {
                    if (isset($categories[$menu['category_id']])) {
                        $menu['category_name'] = $categories[$menu['category_id']]['category_name'];
                    } else {
                        $menu['category_name'] = 'Unknown';
                    }

                    // Log id dari menu untuk pengecekan
                    Log::info('Menu ID:', ['id' => $menu['id']]);

                    $menu['id'] = $menu['id']; // Pastikan id disertakan
                }

                usort($data['data'], function ($a, $b) {
                    return $a['category_id'] <=> $b['category_id'];
                });

                $groupedData = $this->groupByCategory($data['data']);
            }
        } catch (\Exception $e) {
            // Handle error
            Log::error('Error fetching menu data', ['exception' => $e]);
        }

        return view('orders.menu', [
            'banner' => 'MENU',
            'data' => $groupedData
        ]);
    }


    private function mergeOrderDetails($orderDetails)
    {
        $merged = [];

        foreach ($orderDetails as $detail) {
            $key = $detail['category_id'] . '|' . $detail['menu_name'] . '|' . ($detail['note'] ?? '');

            if (isset($merged[$key])) {
                $merged[$key]['quantity'] += $detail['quantity'];
            } else {
                $merged[$key] = $detail;
            }
        }

        return array_values($merged);
    }

    public function addToCart(Request $request)
    {
        $orderDetails = json_decode($request->input('orderDetails'), true);
        $categories = $this->fetchCategories();

        // Ambil menu_ids dari API dan tambahkan ke setiap item order
        $menuIds = $this->getMenuItems();

        foreach ($orderDetails as &$item) {
            if (isset($categories[$item['category_id']])) {
                $item['category_name'] = $categories[$item['category_id']]['category_name'];
            } else {
                $item['category_name'] = 'Unknown';
            }

            // Cari menu_id yang sesuai dengan menu_name
            foreach ($menuIds as $menu) {
                if ($menu['menu_name'] == $item['menu_name']) {
                    $item['menu_id'] = $menu['id'];
                    break;
                }
            }

            // Pastikan menu_id sudah ada di setiap item
            if (!isset($item['menu_id'])) {
                // Handle jika menu_id tidak ditemukan
                Log::error('Menu ID not found for menu: ' . $item['menu_name']);
                return response()->json([
                    'success' => false,
                    'error' => 'Menu ID not found for menu: ' . $item['menu_name']
                ], 400);
            }
        }

        // Gabungkan item yang sama
        $orderDetails = $this->mergeOrderDetails($orderDetails);

        // Simpan ke sesi
        Session::put('basket', $orderDetails);

        Log::info('Add to Cart OrderDetails:', $orderDetails);

        return response()->json([
            'success' => true,
            'redirect' => route('showCart')
        ], 200);
    }



    public function showCart()
    {
        $orderDetails = Session::get('basket', []);
        // Debugging: Cek isi $orderDetails
        Log::info('Show Cart OrderDetails:', $orderDetails);

        if (empty($orderDetails)) {
            return redirect()->route('order.menu');
        }

        return view('orders.cart', compact('orderDetails'));
    }


    public function updateCart(Request $request)
    {
        Log::info('Updating cart:', $request->all());
        $orderDetails = $request->input('orderDetails', []);
        Log::info('Update Cart OrderDetails:', $orderDetails);
        Session::put('basket', $orderDetails);

        return response()->json(['success' => true, 'message' => 'Keranjang berhasil diperbarui!', 'data' => session('basket')]);
    }

    public function logRemoveItem(Request $request)
    {
        $index = $request->input('index');
        $item = $request->input('item');
        
        Log::info('Item removed from cart', [
            'index' => $index,
            'item' => $item
        ]);

        return response()->json(['success' => true]);
    }


    public function checkout(Request $request)
    {
        $orderDetails = $request->input('orderDetails');
        Log::info('Checkout OrderDetails:', $orderDetails);

        // Gabungkan item yang sama
        $orderDetails = $this->mergeOrderDetails($orderDetails);

        Session::put('orderDetails', $orderDetails);

        return redirect()->route('verifyCode');
    }

    public function saveBasket(Request $request)
    {
        $basket = json_decode($request->getContent(), true);
        Session::put('basket', $basket);
        return response()->json(['success' => true]);
    }


    public function verifyCode()
    {
        $orderDetails = Session::get('orderDetails', []);
        Log::info('Verify Code OrderDetails:', $orderDetails);

        if (empty($orderDetails)) {
            return redirect()->route('showCart')->with('error', 'Keranjang Anda kosong.');
        }

        return view('orders.verify', compact('orderDetails'));
    }

    public function confirmOrder(Request $request)
    {
        $orderDetails = Session::get('orderDetails', []);
        $verificationCodeInput = $request->input('verification_code');

        if (empty($orderDetails)) {
            return redirect()->route('showCart')->with('error', 'Order tidak valid.');
        }

        $verificationCode = VerificationCode::where('code', $verificationCodeInput)
                                            ->whereDate('date', Carbon::today())
                                            ->first();

        if (!$verificationCode) {
            return redirect()->back()->with('error', 'Kode verifikasi tidak valid.');
        }

        Log::info('Confirm OrderDetails:', $orderDetails);

        Session::put('verification_code', $verificationCodeInput);

        return redirect()->route('showReceipt');
    }

    public function showReceipt()
    {
        $orderDetails = Session::get('orderDetails', []);
        $verificationCode = Session::get('verification_code', null);

        if (empty($orderDetails) || !$verificationCode) {
            return redirect()->route('showCart')->with('error', 'Order tidak valid atau kode verifikasi tidak ditemukan.');
        }

        $totalPrice = array_reduce($orderDetails, function ($total, $item) {
            return $total + ($item['menu_price'] * $item['quantity']);
        }, 0);

        $order = Order::create([
            'user_id' => auth()->id(),
            'branch_id' => 1, // Ganti dengan branch_id yang sesuai
            'status' => 'pending',
            'total_price' => $totalPrice,
        ]);

        foreach ($orderDetails as $detail) {
            OrderDetail::create([
                'order_id' => $order->id,
                'menu_id' => $detail['menu_id'], // Pastikan menyertakan menu_id
                'menu_name' => $detail['menu_name'],
                'quantity' => $detail['quantity'],
                'menu_price' => $detail['menu_price'],
                'category_id' => $detail['category_id'],
                'note' => $detail['note'] ?? null, // Menyimpan catatan
            ]);
        }

        // Update poin pengguna berdasarkan total harga pesanan
        $user = User::find(auth()->id());
        $pointsEarned = floor($totalPrice / 10000);
        $user->user_points += $pointsEarned;
        $user->save();

        // Ambil detail pesanan yang baru saja dibuat
        $orderDetails = $order->orderDetails;

        Log::info('Show Receipt OrderDetails:', $orderDetails->toArray());

        // Format data seperti yang diminta
        $formattedOrderDetails = [];
        foreach ($orderDetails as $detail) {
            $formattedOrderDetails[] = [
                'id' => $detail->menu_id,
                'category_id' => $detail->category_id,
                'menu_name' => $detail->menu_name,
                'menu_price' => $detail->menu_price,
                'is_available' => 1,
                'created_at' => $detail->created_at->toIso8601String(), // Ubah format ke ISO 8601
                'updated_at' => $detail->updated_at->toIso8601String(), // Ubah format ke ISO 8601
                'count' => $detail->quantity, // Menggunakan 'quantity' sebagai 'count'
            ];
        }

        // Konversi $orderDetails ke array sebelum mengirim ke API
        $orderDetailsArray = $orderDetails->toArray();

        // Kirim pesanan ke API teman Anda
        if ($this->sendOrderToFriendApi($formattedOrderDetails)) {
            Log::info('Order successfully sent to friend API');
            // Reset session setelah berhasil mengirim pesanan
            Session::forget('orderDetails');
            Session::forget('verification_code');
        } else {
            Log::error('Failed to send order to friend API');
            return redirect()->route('showCart')->with('error', 'Gagal mengirim pesanan ke API teman.');
        }

        return view('orders.receipt', compact('order', 'orderDetails', 'pointsEarned', 'formattedOrderDetails'));
    }



    private function sendOrderToFriendApi($formattedOrderDetails)
    {
        $url = 'https://dev-lakeside.matradipti.org/api/order';
        $token = '176|ON1H2gn7fYmYJgrBC8Fc6qubWUarl0AaVZblLQAX';

        $user = auth()->user();

        $client = new Client();

        try {
            $orderData = [
                'order_name' => $user->fullname,
                'order_items' => $formattedOrderDetails,
                'order_payment' => 3, // Ganti dengan metode pembayaran yang sesuai
                'order_total' => array_reduce($formattedOrderDetails, function ($total, $item) {
                    return $total + ($item['menu_price'] * $item['count']);
                }, 0),
                'order_phone_number' => $user->phone,
                'referral_code' => null,
            ];

            // Log orderData sebelum mengirim ke API
            Log::info('Sending order to API with data:', $orderData);

            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $orderData,
            ]);

            $responseData = json_decode($response->getBody(), true);

            Log::info('Response data:', $responseData);

            if ($responseData && isset($responseData['status']) && $responseData['status'] == 'success') {
                Log::info('Order sent successfully to API');
                return true;
            } else {
                Log::error('Failed to send order to API. Response:', $responseData);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error sending order to API: ' . $e->getMessage());
            return false;
        }
    }


    private function groupByCategory($menus)
    {
        $grouped = [];
        foreach ($menus as $menu) {
            $grouped[$menu['category_id']][] = $menu;
        }
        return $grouped;
    }

    private function fetchCategories()
    {
        $client = new Client();
        $url = "https://dev-lakeside.matradipti.org/api/category";

        try {
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                ]
            ]);
            $data = json_decode($response->getBody(), true);

            if ($data && $data['status'] == 'success' && isset($data['data'])) {
                Log::info('Fetched Categories:', $data['data']);
                $categories = [];
                foreach ($data['data'] as $category) {
                    $categories[$category['id']] = $category;
                }
                return $categories;
            } else {
                Log::error('Failed to fetch categories:', (array) $data);
                return [];
            }
        } catch (\Exception $e) {
            Log::error('Error fetching categories:', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function removeItem(Request $request)
    {
        $index = $request->input('index');
        $orderDetails = Session::get('basket', []);

        if (isset($orderDetails[$index])) {
            unset($orderDetails[$index]);
            Session::put('basket', array_values($orderDetails)); // Reindex array
        }

        return response()->json(['success' => true]);
    }

    public function getMenuItems() 
    {
        $url = 'https://dev-lakeside.matradipti.org/api/menu'; // Ganti dengan URL API teman Anda
        $token = '176|ON1H2gn7fYmYJgrBC8Fc6qubWUarl0AaVZblLQAX';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token, // Masukkan token autentikasi di sini
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            // Handle error curl
            return [];
        }

        $responseData = json_decode($response, true);

        if (isset($responseData['data']) && is_array($responseData['data'])) {
            return $responseData['data'];
        } else {
            // Handle error response dari API
            return [];
        }
    }

}
