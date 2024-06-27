<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'product_points',
        'image_path',
        'redeemed'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('redeemed_at');
    }

    public function markAsRedeemed()
    {
        $this->redeemed = true;
        $this->save();
    }
}
