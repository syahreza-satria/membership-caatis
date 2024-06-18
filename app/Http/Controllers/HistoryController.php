<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Reward;
use Illuminate\Http\Request;
use App\Models\rewards_history_log;

class HistoryController extends Controller
{
    public function index()
    {
        return view('history', [
            'banner' => "RIWAYAT"
        ]);
    }

    public function rewardHistory(Request $request)
    {
        $data = rewards_history_log::select(
            "users.user_points",
            "rewards.title",
            "rewards.product_points",
            "rewards_history_log_type.name as annotation",
            "rewards_history_log.created_at as redeemed_at"  // Adjust as needed
        )
        ->leftJoin("users", "users.id", "=", "rewards_history_log.user_id")
        ->leftJoin("rewards", "rewards.id", "=", "rewards_history_log.rewards_id")
        ->leftJoin("rewards_history_log_type", "rewards_history_log_type.id", "=", "rewards_history_log.id")  // Correct join
        ->where("users.id", $request->user()->id)
        ->orderByDesc("rewards_history_log.created_at")
        ->get();

        return view('historyReward', [
            'history' => $data,  // Pass the relevant history data to the view
            'banner' => "RIWAYAT"
        ]);
    }

    public function orderHistory()
    {
        // ambil riwayat pesanan pengguna yang sedang login
        $orders = Order::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();

        return view('historyOrder', [
            'orders' => $orders, 
            'banner' => 'RIWAYAT'
        ]);
    }
}