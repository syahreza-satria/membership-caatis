<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Branch;
use App\Models\Reward;
use Illuminate\Http\Request;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        return view('dashboards.index', compact('totalUsers'));
    }

    public function verificationCodes()
    {
        $today = Carbon::today();
        $code = VerificationCode::where('date', $today)->first();

        if (!$code) {
            $code = $this->generateCode();
        }

        return view('dashboards.verification-codes', [
            'code' => $code,
            'date' => $today
        ]);
    }

    public function generateCode()
    {
        $today = Carbon::today();

        $verificationCode = VerificationCode::firstOrCreate(
            ['date' => $today],
            ['code' => str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT)]
        );

        return $verificationCode->code;
    }

    public function rewards()
    {
        // Mengambil rewards dan mengurutkannya dari yang terbaru sampai yang terlama
        $rewards = Reward::orderBy('created_at', 'desc')->get();
        $branches = Branch::all(); // Ambil semua branches

        return view('dashboards.rewards', compact('rewards', 'branches'));
    }

    public function storeReward(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'product_points' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'branch_id' => 'required|exists:branches,id' // Validasi branch_id
        ]);

        $reward = new Reward;
        $reward->title = $request->title;
        $reward->product_points = $request->product_points;
        $reward->description = $request->description;
        $reward->is_active = true;
        $reward->branch_id = $request->branch_id; // Assign branch_id

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('rewards', 'public');
            $reward->image_path = $imagePath;
        }

        $reward->save();

        $branches = Branch::all(); // Ambil semua branches

        return redirect()->route('dashboard.rewards')->with('success', 'Reward added successfully.');
    }

    public function toggleRewardStatus($id)
    {
        $reward = Reward::findOrFail($id);
        $reward->is_active = !$reward->is_active;
        $reward->save();

        if ($reward->is_active) {
            // Reset users who redeemed this reward
            $reward->users()->detach();
        }

        return redirect()->route('dashboard.rewards')->with('success', 'Reward status updated successfully.');
    }

    public function hideReward($id)
    {
        $reward = Reward::findOrFail($id);
        $reward->is_active = false;
        $reward->save();

        return redirect()->route('dashboard.rewards')->with('success', 'Reward hidden successfully.');
    }

    public function editReward($id)
    {
        $reward = Reward::findOrFail($id);
        return view('dashboards.edit-reward', compact('reward'));
    }

    public function updateReward(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'product_points' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $reward = Reward::findOrFail($id);
        $reward->title = $request->title;
        $reward->product_points = $request->product_points;
        $reward->description = $request->description;

        if ($request->hasFile('image')) {
            if ($reward->image_path) {
                Storage::disk('public')->delete($reward->image_path);
            }
            $imagePath = $request->file('image')->store('rewards', 'public');
            $reward->image_path = $imagePath;
        }

        $reward->save();

        return redirect()->route('dashboard.rewards')->with('success', 'Reward updated successfully.');
    }

    public function destroyReward($id)
    {
        $reward = Reward::findOrFail($id);
        
        // Hapus gambar dari penyimpanan jika ada
        if ($reward->image_path) {
            Storage::disk('public')->delete($reward->image_path);
        }
        
        $reward->delete();

        return redirect()->route('dashboard.rewards')->with('success', 'Reward deleted successfully.');
    }

    public function users()
    {
        $users = User::all();
        $totalUsers = $users->count();

        return view('dashboards.users', compact('users', 'totalUsers'));
    }

    public function orders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255',
            'branch_id' => 'nullable|integer|exists:branches,id'
        ]);

        if ($validator->fails()) {
            return redirect()->route('dashboard.orders')
                            ->withErrors($validator)
                            ->withInput();
        }

        $search = $request->input('search');
        $branch_id = $request->input('branch_id');
        
        $orders = Order::with('user', 'orderDetails', 'branch')
                        ->when($search, function ($query, $search) {
                            return $query->whereHas('user', function ($query) use ($search) {
                                $query->where('fullname', 'like', "%{$search}%");
                            });
                        })
                        ->when($branch_id, function ($query, $branch_id) {
                            return $query->where('branch_id', $branch_id);
                        })
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        $branches = Branch::all(); // Ambil semua branches

        return view('dashboards.orders', compact('orders', 'search', 'branches', 'branch_id'));
    }

    public function searchOrders(Request $request)
    {
        $query = $request->input('query');
        $branch_id = $request->input('branch_id');

        $orders = Order::with('user', 'orderDetails', 'branch')
            ->whereHas('user', function ($q) use ($query) {
                $q->where('fullname', 'like', '%' . $query . '%');
            })
            ->when($branch_id, function ($q) use ($branch_id) {
                $q->where('branch_id', $branch_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }
}
