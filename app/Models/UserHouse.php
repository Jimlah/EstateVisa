<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'house_id',
        'is_owner',
        'status'
    ];
}
