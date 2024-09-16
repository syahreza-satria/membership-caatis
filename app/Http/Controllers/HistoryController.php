<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Branch;
use App\Models\Reward;
use Illuminate\Http\Request;
use App\Models\rewards_history_log;
use Illuminate\Support\Facades\Log;

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
            "rewards.image_path", 
            "rewards_history_log_type.name as annotation",
            "rewards_history_log.created_at as redeemed_at",
            "rewards.branch_id"
        )
        ->leftJoin("users", "users.id", "=", "rewards_history_log.user_id")
        ->leftJoin("rewards", "rewards.id", "=", "rewards_history_log.rewards_id")
        ->leftJoin("rewards_history_log_type", "rewards_history_log_type.id", "=", "rewards_history_log.rewards_history_log_type_id") 
        ->where("users.id", $request->user()->id)
        ->orderByDesc("rewards_history_log.created_at")
        ->get();

        $branches = Branch::all();

        return view('historyReward', [
            'history' => $data,
            'banner' => "RIWAYAT",
            'branches' => $branches
        ]);
    }

    public function orderHistory(Request $request)
    {
        $orders = Order::with(['orderDetails', 'branch:id,logo'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $branches = Branch::select('id', 'logo')->get(); // Optimize query by selecting necessary columns

        return view('historyOrder', [
            'orders' => $orders,
            'branches' => $branches,
            'banner' => "RIWAYAT"
        ]);
    }

}