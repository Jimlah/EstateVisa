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

    public function scopeAdmin($query)
    {
        return $query->where('role', User::ESTATE_ADMIN);
    }

    public function scopeEstateOnly($query)
    {
        return $query->where('estate_id', auth()->user()->estate->first()->id);
    }

    public function getStatAttribute()
    {
        switch ($this->status) {
            case User::ACTIVE:
                return 'Active';
                break;
            case User::SUSPENDED:
                return 'Suspended';
                break;
            case User::DEACTIVATED:
                return 'Suspended';
                break;
            default:
                return 'Unknown';
                break;
        }
    }
}
