<?php

namespace App\Models;

use App\Models\Estate;
use App\Trait\FilterByEstateTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class House_type extends Model
{
    use HasFactory, FilterByEstateTrait;

    protected $fillable = [
        'estate_id',
        'name',
        'description',
        'code'
    ];

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }

    public function house()
    {
        return $this->belongsTo(House::class);
    }
}