<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstateHouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'estate_id',
        'house_id',
    ];

    public function scopeEstateOnly($query)
    {
        return $query->where('estate_id', auth()->user()->estate[0]->id);
    }

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }

    public function house()
    {
        return $this->belongsTo(House::class);
    }
}
