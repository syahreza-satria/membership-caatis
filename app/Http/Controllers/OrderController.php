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
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function showBranch()
    {
        $branches = Branch::all();

        return view('orders.branches', [
            'banner' => 'PEMBELIAN',
            'branches' => $branches
        ]);
    }

    public function pembelian($branch_id)
    {
        $client = new Client();
        $groupedData = [];
        $categories = $this->fetchCategories($branch_id);
        $branchLogo = '';

        try {
            $branch = Branch::findOrFail($branch_id);
            $menuItems = $this->getMenuItems($branch_id);

            // Set the branch logo based on the branch ID
            if ($branch->logo) {
                $branchLogo = Storage::url($branch->logo);
            } else {
                $branchLogo = '/img/default_logo.png'; // Default logo if none found
            }

            if (!empty($menuItems)) {
                foreach ($menuItems as &$menu) {
                    if (isset($categories[$menu['category_id']])) {
                        $menu['category_name'] = $categories[$menu['category_id']]['category_name'];
                    } else {
                        $menu['category_name'] = 'Unknown';
                    }

                    $menu['branch_logo'] = $branchLogo;
                    $menu['id'] = $menu['id'];
                }

                usort($menuItems, function ($a, $b) {
                    return $a['category_id'] <=> $b['category_id'];
                });

                $groupedData = $this->groupByCategory($menuItems);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching menu data', ['exception' => $e]);
        }

        return view('orders.menu', [
            'banner' => 'MENU',
            'data' => $groupedData,
            'branch_id' => $branch_id,
            'branch_logo' => $branchLogo
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
        $orderDetailsJson = $request->input('orderDetails');
        $orderDetails = json_decode($orderDetailsJson, true);

        Log::info('Isi Request:', ['request' => $request->all()]);

        if (is_array($orderDetails) && count($orderDetails) > 0) {
            $branch_id = isset($orderDetails[0]['branch_id']) ? $orderDetails[0]['branch_id'] : Session::get('branch_id');
            if (!$branch_id) {
                Log::error('Branch ID not found in order details or session');
                return response()->json([
                    'success' => false,
                    'error' => 'Branch ID not found in order details or session'
                ], 400);
            }

            Log::info('Branch Id addToCart:', ['branch_id' => $branch_id]);

            Session::put('branch_id', $branch_id);

            $categories = $this->fetchCategories($branch_id);
            $menuIds = $this->getMenuItems($branch_id);

            if (empty($menuIds)) {
                Log::error('Menu IDs not found for branch: ' . $branch_id);
                return response()->json([
                    'success' => false,
                    'error' => 'Menu IDs not found for branch: ' . $branch_id
                ], 400);
            }

            Log::info('Menu IDs found for branch: ' . $branch_id);

            foreach ($orderDetails as &$item) {
                if (isset($categories[$item['category_id']])) {
                    $item['category_name'] = $categories[$item['category_id']]['category_name'];
                } else {
                    $item['category_name'] = 'Unknown';
                }

                foreach ($menuIds as $menu) {
                    if ($menu['menu_name'] == $item['menu_name']) {
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

            $orderDetails = $this->mergeOrderDetails($orderDetails);
            Session::put('basket', $orderDetails);

            Log::info('Add to Cart OrderDetails:', ['orderDetails' => $orderDetails]);

            return response()->json([
                'success' => true,
                'redirect' => route('showCart', ['branch_id' => $branch_id])
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Invalid order details'
            ], 400);
        }
    }

    public function showCart($branch_id)
    {
        $orderDetails = Session::get('basket', []);
        Log::info('Show Cart OrderDetails:', ['orderDetails' => $orderDetails]);

        if (empty($orderDetails)) {
            return redirect()->route('order.menu', ['branch_id' => $branch_id]);
        }

        try {
            $branch = Branch::findOrFail($branch_id);
            $branchLogo = $branch->logo ? Storage::url($branch->logo) : '/img/default_logo.png'; // Default logo if none found

            foreach ($orderDetails as &$item) {
                $item['branch_logo'] = $branchLogo;
            }
        } catch (\Exception $e) {
            Log::error('Error fetching branch data', ['exception' => $e]);
        }

        return view('orders.cart', compact('orderDetails', 'branch_id'));
    }


    public function updateCart(Request $request)
    {
        Log::info('Updating cart:', ['request' => $request->all()]);
        $orderDetails = $request->input('orderDetails', []);
        Log::info('Update Cart OrderDetails:', ['orderDetails' => $orderDetails]);
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
        Log::info('Checkout OrderDetails:', ['orderDetails' => $orderDetails]);

        $orderDetails = $this->mergeOrderDetails($orderDetails);

        Session::put('orderDetails', $orderDetails);

        return redirect()->route('verifyCode');
    }

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

    public function verifyCode()
    {
        $orderDetails = Session::get('orderDetails', []);
        Log::info('Verify Code OrderDetails:', ['orderDetails' => $orderDetails]);

        if (empty($orderDetails)) {
            return redirect()->route('showCart', ['branch_id' => Session::get('branch_id')])->with('error', 'Keranjang Anda kosong.');
        }

        return view('orders.verify', compact('orderDetails'));
    }

    public function confirmOrder(Request $request)
    {
        $orderDetails = Session::get('orderDetails', []);
        $verificationCodeInput = $request->input('verification_code');

        if (empty($orderDetails)) {
            return redirect()->route('showCart', ['branch_id' => Session::get('branch_id')])->with('error', 'Order tidak valid.');
        }

        $verificationCode = VerificationCode::where('code', $verificationCodeInput)
                                            ->whereDate('date', Carbon::today())
                                            ->first();

        if (!$verificationCode) {
            return redirect()->back()->with('error', 'Kode verifikasi tidak valid.');
        }

        Log::info('Confirm OrderDetails:', ['orderDetails' => $orderDetails]);

        Session::put('verification_code', $verificationCodeInput);

        return redirect()->route('showReceipt');
    }

    public function showReceipt()
    {
        $orderDetails = Session::get('orderDetails', []);
        $verificationCode = Session::get('verification_code', null);
        $branch_id = Session::get('branch_id');

        if (empty($orderDetails) || !$verificationCode) {
            return redirect()->route('showCart', ['branch_id' => $branch_id])->with('error', 'Order tidak valid atau kode verifikasi tidak ditemukan.');
        }

        $totalPrice = array_reduce($orderDetails, function ($total, $item) {
            return $total + ($item['menu_price'] * $item['quantity']);
        }, 0);

        $order = Order::create([
            'user_id' => auth()->id(),
            'branch_id' => $branch_id,
            'status' => 'pending',
            'total_price' => $totalPrice,
        ]);

        foreach ($orderDetails as $detail) {
            OrderDetail::create([
                'order_id' => $order->id,
                'menu_id' => $detail['menu_id'],
                'menu_name' => $detail['menu_name'],
                'quantity' => $detail['quantity'],
                'menu_price' => $detail['menu_price'],
                'category_id' => $detail['category_id'],
                'note' => $detail['note'] ?? null,
            ]);
        }

        $user = User::find(auth()->id());
        $pointsEarned = floor($totalPrice / 10000);
        $user->user_points += $pointsEarned;
        $user->save();

        $orderDetails = $order->orderDetails;

        Log::info('Show Receipt OrderDetails:', ['orderDetails' => $orderDetails->toArray()]);

        $formattedOrderDetails = [];
        foreach ($orderDetails as $detail) {
            $formattedOrderDetails[] = [
                'id' => $detail->menu_id,
                'category_id' => $detail->category_id,
                'menu_name' => $detail->menu_name,
                'menu_price' => $detail->menu_price,
                'is_available' => 1,
                'created_at' => $detail->created_at->toIso8601String(),
                'updated_at' => $detail->updated_at->toIso8601String(),
                'count' => $detail->quantity,
            ];
        }

        if ($this->sendOrderToFriendApi($formattedOrderDetails, $branch_id)) {
            Log::info('Order successfully sent to friend API');

            $order->status = 'success';
            $order->save();

            Session::forget('orderDetails');
            Session::forget('verification_code');
        } else {
            Log::error('Failed to send order to friend API');

            $order->status = 'error';
            $order->save();

            return redirect()->route('showCart', ['branch_id' => $branch_id])->with('error', 'Gagal mengirim pesanan ke API teman.');
        }

        return view('orders.receipt', compact('order', 'orderDetails', 'pointsEarned', 'formattedOrderDetails'));
    }

    private function sendOrderToFriendApi($formattedOrderDetails, $branch_id)
    {
        $branch = Branch::find($branch_id);

        if (!$branch || !$branch->api_url || !$branch->api_token) {
            Log::error('Unknown branch ID or missing API details for branch ID: ' . $branch_id);
            return false;
        }

        $url = rtrim($branch->api_url, '/') . '/order';
        $token = $branch->api_token;

        $user = auth()->user();
        $client = new \GuzzleHttp\Client();

        try {
            $orderData = [
                'order_name' => $user->fullname,
                'order_items' => $formattedOrderDetails,
                'order_payment' => 3,
                'order_total' => array_reduce($formattedOrderDetails, function ($total, $item) {
                    return $total + ($item['menu_price'] * $item['count']);
                }, 0),
                'order_phone_number' => $user->phone,
                'referral_code' => null,
            ];

            Log::info('Sending order to API with data:', ['orderData' => $orderData]);

            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $orderData,
            ]);

            $responseData = json_decode($response->getBody(), true);

            Log::info('Response data:', ['responseData' => $responseData]);

            if ($responseData && isset($responseData['status']) && $responseData['status'] == 'success') {
                Log::info('Order sent successfully to API');
                return true;
            } else {
                Log::error('Failed to send order to API. Response:', ['responseData' => $responseData]);
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

    private function fetchCategories($branch_id)
    {
        $client = new Client();
        if ($branch_id == 1) {
            $url = 'https://cashier.matradipti.org/api/category';
            $token = '13225|X0P930aqQqd1lJhV671oCxMT7TNwnVFdx1JeEspt';
        } elseif ($branch_id == 2) {
            $url = 'https://lakesidefit.matradipti.org/api/category';
            $token = '12412|chgOcN0JHfShyzi7kc9oLEsk6at9vQbrMI49gjew';
        } elseif ($branch_id == 3) {
            $url = 'https://literasicafe.matradipti.org/api/category';
            $token = '12611|TYa3BCfoS1ETBIDUXUcb3dHmcKnOAOQk3sMoqPzO';
        } else {
            Log::error('fetchCategories Unknown branch ID: ' . $branch_id);
            return [];
        }

        try {
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);
            $data = json_decode($response->getBody(), true);

            if ($data && $data['status'] == 'success' && isset($data['data'])) {
                Log::info('Fetched Categories:', ['categories' => $data['data']]);
                $categories = [];
                foreach ($data['data'] as $category) {
                    $categories[$category['id']] = $category;
                }
                return $categories;
            } else {
                Log::error('Failed to fetch categories:', ['data' => $data]);
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
            Session::put('basket', array_values($orderDetails));
        }

        return response()->json(['success' => true]);
    }

    public function getMenuItems($branch_id)
    {
        $client = new \GuzzleHttp\Client();

        // Ambil URL dan token dari tabel Branch
        $branch = Branch::find($branch_id);

        if (!$branch || !$branch->api_url || !$branch->api_token) {
            Log::error('getMenuItems: Invalid branch ID or missing API details for branch ID: ' . $branch_id);
            return [];
        }

        $url = rtrim($branch->api_url, '/') . '/menu/available';
        $token = $branch->api_token;

        try {
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);

            if (isset($responseData['data']) && is_array($responseData['data'])) {
                return $responseData['data'];
            } else {
                return [];
            }
        } catch (\Exception $e) {
            Log::error('Error fetching menu data for branch ID ' . $branch_id . ': ' . $e->getMessage());
            return [];
        }
    }
}
