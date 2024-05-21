<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rewards_history_log extends Model
{
    use HasFactory;

    protected $table = "rewards_history_log";
    protected $fillable = [
        'user_id',
        'rewards_id',
        'rewards_history_log_type_id'
    ];


}
