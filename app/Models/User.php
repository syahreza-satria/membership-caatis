<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $fillable = [
        'fullname', 'email', 'password', 'phone', 'user_points', 'is_admin'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addPoints($points)
    {
        $this->user_points += $points;
        $this->save();
    }

    public function rewards()
    {
        return $this->belongsToMany(Reward::class)->withPivot('redeemed_at');
    }

    public function redeemReward(Reward $reward)
    {
        if($this->user_points >= $reward->product_points){
            $this->user_points -= $reward->product_points;
            $this->save();
            return true;
        } else {
            return false;
        }
    }
}
