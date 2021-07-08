<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;

    public function estate()
    {
        $this->belongsTo(Estate::class);
    }

    public function houseType()
    {
        return $this->hasOne(House_type::class);
    }
}
