<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public function rewards(){
        return $this->belongsToMany(Reward::class)->withPivot('redeemed_at');
    }

    public function addPoints($points)
    {
        $this->user_points += $points;
        $this->save();
    }

    public function redeemReward(Reward $reward)
    {
        if($this->user_points >= $reward->product_points){
            $this->user_points = $this->user_points - $reward->product_points;
            $this->save();
            return true;
        } else{
            return false;
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'user_points'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
