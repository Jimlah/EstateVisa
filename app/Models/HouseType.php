<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'estate_id'
    ];

    public function scopeEstateOnly($query)
    {
        return $query->where('estate_id', auth()->user()->estate->first()->id);
    }

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }
}
