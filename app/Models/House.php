<?php

namespace App\Models;

use App\Models\HouseType;
use App\Models\HouseUser;
use App\Models\UserHouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class House extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public function scopeEstateHouses($query)
    {
        return $query->where('estate_id', auth()->user()->estate->first()->id);
    }

    public function houseUsers()
    {
        return $this->hasMany(UserHouse::class);
    }

    public function owner()
    {
        return  $this->houseUsers()
            ->where('is_owner', true)
            ->first();
    }

    public function members()
    {
        return $this->houseUsers()
            ->where('is_owner', false);
    }
}
