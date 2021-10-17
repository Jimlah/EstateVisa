<?php

namespace App\Models;

use App\Models\User;
use App\Models\Estate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'estate_id',
        'firstname',
        'lastname',
        'email',
        'phone',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }

    public function scopeUserOnly($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }

    public function scopeEstateOnly($query)
    {
        return $query->where('estate_id', auth()->user()->estate->first()->id);
    }
}
