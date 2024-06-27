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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        session()->forget('basket');
        // dd(request()->tag);
        return view('rewards.index',[
            'rewards' => Reward::all(),
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
        return view('rewards.show', [
            'reward' => $reward,
            'banner' => 'MY REWARDS'
        ]);
    }

    public function redeemPoints(Request $request, int $reward)
    {
        $user = \App\Models\User::findOrFail($request->user()->id);
        $rewardModel = \App\Models\Reward::findOrFail($reward);

        if($user->redeemReward($rewardModel)){
            $user->rewards()->attach($rewardModel->id, ['redeemed_at' => now()]);
            \App\Models\rewards_history_log::create([
                "user_id" => $user->id,
                "rewards_id" => $rewardModel->id,
                "rewards_history_log_type_id" => 2 
            ]);
            
            // $rewardModel->redeemed = true;
            // $rewardModel->save();

            return redirect('/')->with('success', 'Poin kamu telah berhasil ditukarkan!');
        }else{
            return redirect()->back()->with('error', 'Kamu tidak memiliki poin yang mencukupi :(');
        }
    }
}