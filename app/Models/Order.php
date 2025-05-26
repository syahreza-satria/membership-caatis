<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'branch_id', 'status', 'total_price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'outletId');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
