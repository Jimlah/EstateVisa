<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HouseOwner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'estate_id',
        'house_id',
        'status'
    ];

    protected static function booted()
    {
        static::addGlobalScope('house_owner', function ($builder) {
            if (auth()->check()) {
                $builder->where('user_id', auth()->user()->id);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function estate()
    {
        return $this->belongsTo(Estate::class, 'estate_id');
    }

    public function house()
    {
        return $this->belongsTo(House::class, 'house_id');
    }
}
