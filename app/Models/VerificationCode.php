<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'date'
    ];

    public function scopeValid($query)
    {
        return $query->whereDate('date', Carbon::today());
    }

}
