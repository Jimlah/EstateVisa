<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House_type extends Model
{
    use HasFactory;

    public function house()
    {
        $this->belongsTo(House::class);
    }
}
