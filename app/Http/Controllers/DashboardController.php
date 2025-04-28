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
        $today = Carbon::today();
        $code = VerificationCode::where('date', $today)->first();
        $branches = Branch::all();
        $orders = Order::all();
        $users = User::all();
        $rewards = Reward::all();

        if (!$code) {
            $code = $this->generateCode();
        }

        return view('dashboards.index', compact('totalUsers', 'code', 'branches', 'orders', 'users', 'rewards'));
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

        $verificationCode = VerificationCode::firstOrNew(['date' => $today]);

        if (!$verificationCode->exists) {
            $verificationCode->code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $verificationCode->date = $today;
            $verificationCode->save();
        }

        return $verificationCode->code;
    }

    public function rewards()
    {
        $rewards = Reward::orderBy('created_at', 'desc')->get();
        $branches = Branch::all();

        return view('dashboards.rewards', compact('rewards', 'branches'));
    }

    public function storeReward(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'product_points' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'branch_id' => 'required|exists:branches,id'
        ]);

        $reward = new Reward;
        $reward->title = $request->title;
        $reward->product_points = $request->product_points;
        $reward->description = $request->description;
        $reward->is_active = true;
        $reward->branch_id = $request->branch_id;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('rewards', 'public');
            $reward->image_path = $imagePath;
        }

        $reward->save();

        return redirect()->route('dashboard.rewards')->with('success', 'Reward added successfully.');
    }

    public function toggleRewardStatus($id)
    {
        $reward = Reward::findOrFail($id);
        $reward->is_active = !$reward->is_active;
        $reward->save();

        if ($reward->is_active) {
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
            'branch_id' => 'nullable|string|exists:branches,outletId' // Gunakan outletId
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
                            return $query->where(function ($query) use ($search) {
                                $query->whereHas('user', function ($subQuery) use ($search) {
                                    $subQuery->where('fullname', 'like', "%{$search}%");
                                })->orWhereHas('branch', function ($subQuery) use ($search) {
                                    $subQuery->where('name', 'like', "%{$search}%");
                                })->orWhere('total_price', 'like', "%{$search}%");
                            });
                        })
                        ->when($branch_id, function ($query, $branch_id) {
                            return $query->whereHas('branch', function ($subQuery) use ($branch_id) {
                                $subQuery->where('outletId', $branch_id);
                            });
                        })
                        ->orderBy('created_at', 'desc')
                        ->get();

        $branches = Branch::all();

        return view('dashboards.orders', compact('orders', 'search', 'branches', 'branch_id'));
    }

    public function searchOrders(Request $request)
    {
        $query = $request->input('query');
        $branch_id = $request->input('branch_id');

        $orders = Order::with('user', 'orderDetails', 'branch')
            ->where(function ($q) use ($query) {
                if ($query) {
                    $q->whereHas('user', function ($subQuery) use ($query) {
                        $subQuery->where('fullname', 'like', "%{$query}%");
                    })->orWhereHas('branch', function ($subQuery) use ($query) {
                        $subQuery->where('name', 'like', "%{$query}%");
                    })->orWhere('total_price', 'like', "%{$query}%");
                }
            })
            ->when($branch_id, function ($q) use ($branch_id) {
                return $q->whereHas('branch', function ($subQuery) use ($branch_id) {
                    $subQuery->where('outletId', $branch_id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function branches()
    {
        $branches = Branch::all();
        return view('dashboards.branches.index', compact('branches'));
    }

    public function createBranch()
    {
        return view('dashboards.branches.create');
    }

    public function storeBranch(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'api_url' => 'nullable|string|max:255',
            'api_token' => 'nullable|string|max:255',
            'outletId' => 'nullable|string|max:255',
            'order_type' => 'nullable|string|max:255'
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        Branch::create($data);

        return redirect()->route('dashboard.branches')->with('success', 'Branch created successfully.');
    }

    public function editBranch(Branch $branch)
    {
        return view('dashboards.branches.edit', compact('branch'));
    }

    public function updateBranch(Request $request, Branch $branch)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'api_url' => 'nullable|string|max:255',
            'api_token' => 'nullable|string|max:255',
            'outletId' => 'nullable|string|max:255',
            'order_type' => 'nullable|string|max:255'
        ]);

        if ($request->hasFile('logo')) {
            if ($branch->logo) {
                Storage::delete($branch->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $branch->update($data);

        return redirect()->route('dashboard.branches')->with('success', 'Branch updated successfully.');
    }

    public function destroyBranch(Branch $branch)
    {
        if ($branch->logo) {
            Storage::delete($branch->logo);
        }

        $branch->delete();

        return redirect()->route('dashboard.branches')->with('success', 'Branch deleted successfully.');
    }
}
