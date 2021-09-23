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

    protected $with = [
        'user',
        'estate',
        'user.profile'
    ];

    // protected static function booted()
    // {
    //     static::addGlobalScope('estate_super_admin', function ($builder) {
    //         $builder->where('role', User::ESTATE_SUPER_ADMIN);
    //     });
    // }

    public function scopeOwner($query)
    {
        return $query->where('role', User::ESTATE_SUPER_ADMIN);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }
}