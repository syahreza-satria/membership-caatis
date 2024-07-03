<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'logo', 'api_url', 'api_token'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function rewards()
    {
        return $this->hasMany(Reward::class);
    }
}

