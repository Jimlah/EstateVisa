<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstateModule extends Model
{
    use HasFactory;

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
