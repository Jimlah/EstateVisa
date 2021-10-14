<?php

namespace App\Models;

use App\Models\HouseType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class House extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'house_type_id',
        'description'
    ];

    public function estate()
    {
        return $this->belongsToMany(Estate::class, 'estate_houses', 'house_id', 'estate_id');
    }

    public function houseType()
    {
        return $this->belongsTo(HouseType::class, 'house_type_id');
    }

    public function houseOwner()
    {
        return $this->hasOne(HouseOwner::class, 'house_id');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'house_owners', 'house_id', 'user_id');
    }
}
