<?php

namespace App\Models;

use App\Models\HouseType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class House extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'estate_id',
        'name',
        'address',
        'house_type_id',
        'status',
        'description'
    ];

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }

    public function houseType()
    {
        return $this->belongsTo(HouseType::class, 'house_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeEstateHouses($query)
    {
        return $query->where('estate_id', auth()->user()->estate->first()->id);
    }

    public function scopeUserHouses($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }
}
