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


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function scopeUserHouse($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }
}
