<?php

namespace App\Models;

use App\Models\HouseType;
use App\Models\EstateHouse;
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
}
