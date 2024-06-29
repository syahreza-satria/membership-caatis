<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreRewardRequest;
use App\Http\Requests\UpdateRewardRequest;

class RewardController extends Controller
{
    public function index()
    {
        session()->forget('basket');
        return view('rewards.index', [
            'rewards' => Reward::with('branch')->where('is_active', true)->orderBy('created_at', 'desc')->get(),
            'banner' => 'MY REWARDS'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reward  $reward
     * @return \Illuminate\Http\Response
     */
    public function show(Reward $reward)
    {
        // Check if the reward is active before showing it
        if (!$reward->is_active) {
            return redirect()->route('rewards.index')->with('error', 'Reward ini tidak aktif.');
        }

        return view('rewards.show', [
            'reward' => $reward,
            'banner' => 'MY REWARDS'
        ]);
    }

    public function redeemPoints(Request $request, int $reward)
    {
        $user = User::findOrFail($request->user()->id);
        $rewardModel = Reward::findOrFail($reward);

        if ($rewardModel->is_active && $user->redeemReward($rewardModel)) {
            $user->rewards()->attach($rewardModel->id, ['redeemed_at' => now()]);
            \App\Models\rewards_history_log::create([
                "user_id" => $user->id,
                "rewards_id" => $rewardModel->id,
                "rewards_history_log_type_id" => 2
            ]);

            return redirect('/')->with('success', 'Poin kamu telah berhasil ditukarkan!');
        } else {
            return redirect()->back()->with('error', 'Kamu tidak memiliki poin yang mencukupi atau reward ini tidak aktif.');
        }
    }
}
