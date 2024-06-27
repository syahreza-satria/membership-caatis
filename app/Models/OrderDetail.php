<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id','menu_id', 'menu_name', 'quantity', 'menu_price', 'category_id', 'note'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
