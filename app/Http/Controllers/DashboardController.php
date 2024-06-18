<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Reward;
use Illuminate\Http\Request;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Storage;

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
        $rewards = Reward::all();
        return view('dashboards.rewards', compact('rewards'));
    }

    public function storeReward(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'product_points' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $reward = new Reward;
        $reward->title = $request->title;
        $reward->product_points = $request->product_points;
        $reward->description = $request->description;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('rewards', 'public');
            $reward->image_path = $imagePath;
        }

        $reward->save();

        return redirect()->route('dashboard.rewards')->with('success', 'Reward added successfully.');

    }

    public function destroyReward($id)
    {
        $reward = Reward::findOrFail($id);

        // Hapus gambar dari penyimpanan
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
            // Delete the old image
            if ($reward->image_path) {
                Storage::disk('public')->delete($reward->image_path);
            }
            // Store the new image
            $imagePath = $request->file('image')->store('rewards', 'public');
            $reward->image_path = $imagePath;
        }

        $reward->save();

        return redirect()->route('dashboard.rewards')->with('success', 'Reward updated successfully.');

    }
}
