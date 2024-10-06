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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    // Menampilkan branch
    public function showBranch()
    {
        $branches = Branch::all();

        return view('orders.branches', [
            'banner' => 'PEMBELIAN',
            'branches' => $branches
        ]);
    }

    // Method untuk menampilkan menu berdasarkan branch_id
    public function pembelian($branch_id)
    {
        $groupedData = [];
        $categories = $this->fetchCategories($branch_id);  // Mengambil kategori
        $menuItems = $this->getMenuItems($branch_id);  // Mengambil menu items
        $branch = Branch::where('outletId', $branch_id)->first();

        try {
            if (!empty($menuItems)) {
                foreach ($menuItems as $menu) {
                    // Menambahkan nama kategori dari kategori yang diambil
                    if (isset($categories[$menu['category_id']])) {
                        $menu['category_name'] = $categories[$menu['category_id']]['category_name'];
                    } else {
                        $menu['category_name'] = 'Unknown';
                    }
                }

                // Mengelompokkan menu berdasarkan kategori
                usort($menuItems, function ($a, $b) {
                    return $a['category_id'] <=> $b['category_id'];
                });

                $groupedData = $this->groupByCategory($menuItems);
            }
            
        } catch (\Exception $e) {
            Log::error('Error fetching menu data', ['exception' => $e]);
        }

        return view('orders.menu', [
            'data' => $groupedData,
            'branch' => $branch,
            'branch_id' => $branch_id
        ]);
    }

    // Method untuk menambahkan item ke keranjang (cart)
    public function addToCart(Request $request)
    {
        try {
            // Ambil data order dari request
            $orderDetailsJson = $request->input('orderDetails');
            $orderDetails = json_decode($orderDetailsJson, true);

            Log::info('Isi Request:', ['request' => $request->all()]);

            // Validasi apakah orderDetails berisi data
            if (is_array($orderDetails) && count($orderDetails) > 0) {
                // Cek branch_id dari orderDetails atau session
                $branch_id = isset($orderDetails[0]['branch_id']) ? $orderDetails[0]['branch_id'] : Session::get('branch_id');
                if (!$branch_id) {
                    Log::error('Branch ID not found in order details or session');
                    return response()->json([
                        'success' => false,
                        'error' => 'Branch ID not found in order details or session'
                    ], 400);
                }

                // Simpan branch_id ke dalam session jika belum disimpan
                Session::put('branch_id', $branch_id); // Simpan branch_id ke session

                // Fetch categories dan menu items dari branch_id
                $categories = $this->fetchCategories($branch_id);
                $menuIds = $this->getMenuItems($branch_id);
                Log::info('category', ['category' => $categories]);

                if (empty($menuIds)) {
                    Log::error('Menu IDs not found for branch: ' . $branch_id);
                    return response()->json([
                        'success' => false,
                        'error' => 'Menu IDs not found for branch: ' . $branch_id
                    ], 400);
                }

                // Proses orderDetails, menambahkan kategori dan menu_id
                $categoryMap = array_column($categories, 'category_name', 'id');
                foreach ($orderDetails as &$item) {
                    // $item['category_name'] = $categories[$item['category_id']]['category_name'] ?? 'Unknown';
                    $item['category_name'] = $categoryMap[$item['category_id']] ?? 'Unknown';

                    foreach ($menuIds as $menu) {
                        if ($menu['name'] == $item['menu_name']) {
                            $item['menu_id'] = $menu['id'];
                            break;
                        }
                    }

                    if (!isset($item['menu_id'])) {
                        Log::error('Menu ID not found for menu: ' . $item['menu_name']);
                        return response()->json([
                            'success' => false,
                            'error' => 'Menu ID not found for menu: ' . $item['menu_name']
                        ], 400);
                    }
                }

                // Gabungkan item order jika ada yang sama
                $orderDetails = $this->mergeOrderDetails($orderDetails);
                Session::put('basket', $orderDetails);

                Log::info('Add to Cart OrderDetails:', ['orderDetails' => $orderDetails]);

                // Kirim respons sukses dan arahkan ke cart
                return response()->json([
                    'success' => true,
                    'redirect' => route('showCart', ['outletId' => $branch_id])
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid order details'
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error di addToCart:', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'error' => 'Internal server error. Silakan coba lagi nanti.'. $e
            ], 500);
        }
    }


    // Menampilkan keranjang (cart)
    public function showCart($outletId)
    {
        $orderDetails = Session::get('basket', []);
        Log::info('Show Cart OrderDetails:', ['orderDetails' => $orderDetails]);

        if (empty($orderDetails)) {
            return redirect()->route('order.menu', ['branch_id' => $outletId]);
        }

        try {
            $branch = Branch::where('outletId', $outletId)->first();  // Menggunakan first untuk menghindari error
            if ($branch) {
                $branchLogo = $branch->logo ? Storage::url($branch->logo) : '/img/default_logo.png'; // Default logo jika tidak ada
                foreach ($orderDetails as &$item) {
                    $item['branch_logo'] = $branchLogo;
                }
            } else {
                // Jika branch tidak ditemukan, berikan logo default
                $branchLogo = '/img/default_logo.png';
                foreach ($orderDetails as &$item) {
                    $item['branch_logo'] = $branchLogo;
                }
                Log::warning('Branch ID not found: ' . $outletId);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching branch data', ['exception' => $e]);
        }

        return view('orders.cart', compact('orderDetails', 'outletId'));
    }

    // Mengupdate keranjang
    public function updateCart(Request $request)
    {
        Log::info('Updating cart:', ['request' => $request->all()]);
        $orderDetails = $request->input('orderDetails', []);
        Log::info('Update Cart OrderDetails:', ['orderDetails' => $orderDetails]);
        Session::put('basket', $orderDetails);

        return response()->json(['success' => true, 'message' => 'Keranjang berhasil diperbarui!', 'data' => session('basket')]);
    }

    public function removeItem(Request $request)
    {
        $index = $request->input('index');
        $orderDetails = Session::get('basket', []);

        if (isset($orderDetails[$index])) {
            unset($orderDetails[$index]);  // Hapus item berdasarkan index
            Session::put('basket', array_values($orderDetails));  // Reset indeks array setelah penghapusan
        }

        return response()->json(['success' => true]);
    }

    // Menangani penghapusan item dari keranjang
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

    // Menangani checkout
    public function checkout(Request $request)
    {
        $orderDetails = $request->input('orderDetails');
        Log::info('Checkout OrderDetails:', ['orderDetails' => $orderDetails]);

        $orderDetails = $this->mergeOrderDetails($orderDetails);

        Session::put('orderDetails', $orderDetails);

        return redirect()->route('verifyCode');
    }

        // Verifikasi kode pesanan
    public function verifyCode()
    {
        $orderDetails = Session::get('orderDetails', []);
        Log::info('Verify Code OrderDetails:', ['orderDetails' => $orderDetails]);

        if (empty($orderDetails)) {
            // Pastikan bahwa branch_id diambil dari session dengan benar
            $branch_id = Session::get('branch_id');
            if (!$branch_id) {
                Log::error('Branch ID not found in session during verifyCode');
                return redirect()->back()->with('error', 'Branch ID tidak ditemukan.');
            }
            return redirect()->route('showCart', ['outletId' => $branch_id])->with('error', 'Keranjang Anda kosong.');
        }

        return view('orders.verify', compact('orderDetails'));
    }

    // Konfirmasi pesanan
    public function confirmOrder(Request $request)
    {
        // Ambil orderDetails dari session
        $orderDetails = Session::get('orderDetails', []);
        $verificationCodeInput = $request->input('verification_code');

        // Cek jika branch_id dalam session
        $branch_id = Session::get('branch_id');
        if (!$branch_id) {
            Log::error('Branch ID not found in session during confirmOrder');
            return redirect()->back()->with('error', 'Branch ID tidak ditemukan.');
        }

        // Cek apakah branch_id sudah ada di session
        $branch_id = Session::get('branch_id');

        // Jika branch_id sudah ada di session, perbarui nilai dengan id dari tabel Branch
        $branch = Branch::where('outletId', $branch_id)->first();

        // Jika branch ditemukan, update session branch_id dengan branch->id
        if ($branch) {
            $branch_id = $branch->id; // Isi session branch_id dengan id dari tabel Branch
            Session::put('branch_id', $branch_id); // Simpan ke session

            Log::info('Session branch_id updated with branch->id', [
                'user_id' => auth()->id(),
                'new_branch_id' => $branch_id
            ]);
        } else {
            // Jika tidak ditemukan, log error dan kembalikan dengan error
            Log::warning('Branch not found for session branch_id', [
                'branch_id' => $branch_id,
                'user_id' => auth()->id()
            ]);
            return redirect()->back()->with('error', 'Branch tidak ditemukan.');
        }

        // Setelah ini, Anda bisa melanjutkan proses logika yang lain seperti biasa
        // Misalnya, jika Anda tetap ingin menggunakan outletId di bagian berikut, sesuaikan saja dengan logika bisnis Anda

        // Contoh: Mengupdate session branch_id menjadi outletId jika diperlukan
        if ($branch && $branch->outletId) {
            Session::put('branch_id', $branch->outletId); // Simpan outletId ke session (jika memang perlu)
            Log::info('Session branch_id updated to outletId', [
                'user_id' => auth()->id(),
                'old_branch_id' => $branch_id,
                'new_outletId' => $branch->outletId
            ]);
        }

        // Cari branch berdasarkan branch_id
        $branch = Branch::where('id', $branch_id)->first();
        if ($branch && $branch->outletId) {
            // Update session branch_id menjadi outletId
            Session::put('branch_id', $branch->outletId);
            Log::info('Session branch_id updated to outletId', [
                'user_id' => auth()->id(),
                'old_branch_id' => $branch_id,
                'new_outletId' => $branch->outletId
            ]);
        } else {
            Log::warning('Branch not found for session branch_id', [
                'branch_id' => $branch_id,
                'user_id' => auth()->id()
            ]);
            return redirect()->back()->with('error', 'Branch tidak ditemukan.');
        }

        // Jika orderDetails kosong
        if (empty($orderDetails)) {
            Log::info('Order confirmation failed: orderDetails is empty', [
                'user_id' => auth()->id(),
                'session_branch_id' => Session::get('branch_id'),
                'session_orderDetails' => Session::get('orderDetails'),
                'action' => 'redirect to showCart',
                'reason' => 'Empty orderDetails in session'
            ]);

            return redirect()->route('showCart', ['outletId' => Session::get('branch_id')])->with('error', 'Order tidak valid.');
        }

        // Verifikasi kode
        $verificationCode = VerificationCode::where('code', $verificationCodeInput)
                                            ->whereDate('date', Carbon::today())
                                            ->first();

        if (!$verificationCode) {
            Log::info('Order confirmation failed: invalid verification code', [
                'user_id' => auth()->id(),
                'input_code' => $verificationCodeInput,
                'orderDetails' => $orderDetails,
                'session_branch_id' => Session::get('branch_id'),
                'date' => Carbon::today()->toDateString(),
                'action' => 'redirect back',
                'reason' => 'Invalid verification code'
            ]);

            return redirect()->back()->with('error', 'Kode verifikasi tidak valid.');
        }

        Log::info('Order confirmed successfully', [
            'user_id' => auth()->id(),
            'verification_code' => $verificationCodeInput,
            'orderDetails' => $orderDetails,
            'session_branch_id' => Session::get('branch_id'),
            'date' => Carbon::today()->toDateTimeString()
        ]);

        // Menyimpan kode verifikasi di session
        Session::put('verification_code', $verificationCodeInput);

        // Pembuatan order dilakukan di sini
        $totalPrice = array_reduce($orderDetails, function ($total, $item) {
            return $total + ($item['menu_price'] * $item['quantity']);
        }, 0);

        $order = Order::create([
            'user_id' => auth()->id(),
            'branch_id' => $branch->outletId,
            'status' => 'pending',
            'total_price' => $totalPrice,
        ]);

        foreach ($orderDetails as $detail) {
            OrderDetail::create([
                'order_id' => $order->id, // Menghubungkan dengan order yang baru saja dibuat
                'menu_id' => $detail['menu_id'],
                'menu_name' => $detail['menu_name'],
                'quantity' => $detail['quantity'],
                'menu_price' => $detail['menu_price'],
                'category_id' => $detail['category_id'],
                'note' => $detail['note'] ?? '',
            ]);
        }

        // Pengiriman ke API Teman dilakukan di sini
        $formattedOrderDetails = [];
        foreach ($orderDetails as $detail) {
            $variant_Id = $this->fetchVariant($branch->outletId, $detail['menu_id']);
            $formattedOrderDetails[] = [
                'product_id' => $detail['menu_id'],
                'variant_id' => $variant_Id,
                'modifier_option_ids' => [],
                'qty' => $detail['quantity'],
                'notes' => $detail['note'] ?? '',
            ];
        }

        $orderData = [
            'customer_name' => auth()->user()->fullname,
            'phone_number' => auth()->user()->phone,
            'identity' => '-',
            'outlet_id' => $branch->outletId,
            'order_payment' => 3,
            'order_totals' => $totalPrice,
            'order_details' => $formattedOrderDetails,
        ];

        // Kirim data ke API teman
        $this->sendOrderToFriendApi($orderData, $branch->api_url, $branch->api_token);

        // Update poin user
        $user = User::find(auth()->id());
        $pointsEarned = floor($totalPrice / 10000);
        $user->user_points += $pointsEarned;
        $user->save();

        // Simpan data yang diperlukan di session
        Session::put('order_id', $order->id);
        Session::put('points_earned', $pointsEarned);

        return redirect()->route('showReceipt');
    }


    // Menampilkan struk pesanan
    public function showReceipt()
    {
        Log::info(Session::all());
        $orderDetails = Session::get('orderDetails', []);
        $verificationCode = Session::get('verification_code', null);
        $outletId = Session::get('branch_id'); // Ini adalah outletId, bukan branch_id
        $orderId = Session::get('order_id');
        $pointsEarned = Session::get('points_earned', 0);

        if (empty($orderDetails) || !$verificationCode) {
            return redirect()->route('showCart', ['outletId' => $outletId])->with('error', 'Order tidak valid atau kode verifikasi tidak ditemukan.');
        }

        // Cari order berdasarkan order_id
        $order = Order::find($orderId);

        return view('orders.receipt', compact('order', 'orderDetails', 'pointsEarned'));
    }

    
    // private function 
    private function fetchVariant($outletId, $menuId)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Api-Key' => 'p0s-fnb-@p1-t0k3n-2024-xnxx'
        ])->get("https://pos.lakesidefnb.group/api/mobile/product/outlet/{$outletId}");

        if ($response->successful()) {
            $data = $response->json()['data'];

            // Cari produk berdasarkan menuId
            $product = collect($data)->firstWhere('id', $menuId);

            if($product){
                // Ambil variant Id dari produk
                $variantIds = collect($product['variants'])->pluck('id');

                if ($variantIds->isNotEmpty()) {
                    return $variantIds->first();
                } else{
                    Log::warning("No variant found for Product ID: {$menuId}");
                    return null;
                }
            }else{
                Log::warning("Product Not Found: {$menuId}");
                return null;
            }
        } else{
            Log::error("Failed to fetch product data from API.");
            return null; // Kembalikan null jika API gagal diakses
        }
    }
    
    // Mengirim pesanan ke API teman
    private function sendOrderToFriendApi($orderData, $apiUrl, $apiToken)
    {
        // Log data yang dikirim sebelum mengirim request
        Log::info('Preparing to send order', [
            'orderData' => $orderData,
            'apiUrl' => $apiUrl,
            'apiToken' => $apiToken
        ]);

        $client = new \GuzzleHttp\Client();

        try {
            // Lengkapi URL dengan endpoint yang sesuai
            $fullApiUrl = rtrim($apiUrl, '/') . '/mobile/order';
            
            // Log URL final yang digunakan
            Log::info('Full API URL', ['url' => $fullApiUrl]);

            // Kirim request ke API teman
            $response = $client->post($fullApiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'X-Api-Key' => $apiToken, // Gunakan token dari branch
                ],
                'json' => $orderData, // Kirim data dalam format JSON
            ]);

            // Dapatkan respons body dan status
            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody(), true);

            // Cek status dari response
            if (isset($responseData['status']) && $responseData['status'] === 'success') {
                Log::info('Order sent successfully to friend API');
                return true;
            } else {
                Log::error('Failed to send order to API', [
                    'response' => $responseData,
                    'status_code' => $statusCode
                ]);
                return false;
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Tangani error khusus Guzzle
            if ($e->hasResponse()) {
                // Log respons error dari API
                $errorResponse = (string) $e->getResponse()->getBody();
                $statusCode = $e->getResponse()->getStatusCode();
                Log::error('API Request Exception', [
                    'error' => $errorResponse,
                    'status_code' => $statusCode
                ]);
            } else {
                Log::error('Error sending order to API: ' . $e->getMessage());
            }
            return false;
        } catch (\Exception $e) {
            // Tangani error lain-lain
            Log::error('General Error sending order to API: ' . $e->getMessage());
            return false;
        }
    }

    // Menggabungkan item order berdasarkan kategori dan menu
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

    // Menyimpan keranjang (basket)
    public function saveBasket(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        Log::info('Save Basket Data:', ['data' => $data]);

        $basket = $data['basket'];
        $branch_id = $data['branch_id'];

        Session::put('basket', $basket);
        Session::put('branch_id', $branch_id);

        return response()->json(['success' => true]);
    }



    // Mengelompokkan menu berdasarkan kategori
    private function groupByCategory($menus)
    {
        $grouped = [];
        foreach ($menus as $menu) {
            $grouped[$menu['category_id']][] = $menu;
        }
        return $grouped;
    }

    // Mengambil data kategori dari API
    private function fetchCategories($branch_id)
    {
        $apiUrl = "https://pos.lakesidefnb.group/api/mobile/category/outlet/{$branch_id}";
        $apiKey = 'p0s-fnb-@p1-t0k3n-2024-xnxx'; 

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Api-Key' => $apiKey
        ])->get($apiUrl);

        if ($response->successful()) {
            return $response->json()['data'];  
        } else {
            return [];
        }
    }

    // Mengambil menu items berdasarkan branch_id
    public function getMenuItems($branch_id)
    {
        $apiUrl = "https://pos.lakesidefnb.group/api/mobile/product/outlet/{$branch_id}";
        $apiKey = 'p0s-fnb-@p1-t0k3n-2024-xnxx'; 

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Api-Key' => $apiKey
        ])->get($apiUrl);

        if ($response->successful()) {
            return $response->json()['data'];  
        } else {
            return [];
        }
    }
}