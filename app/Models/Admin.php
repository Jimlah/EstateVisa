<?php

namespace App\Models;

use App\Models\User;
use App\Trait\UseDisable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    use HasFactory;
    use UseDisable;

    protected $fillable = [
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
