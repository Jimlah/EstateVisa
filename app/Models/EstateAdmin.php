<?php

namespace App\Models;

use App\Models\User;
use App\Trait\UseDisable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstateAdmin extends Model
{
    use HasFactory;
    use UseDisable;

    protected $fillable = [
        'user_id',
        'estate_id',
        'role',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }
}
