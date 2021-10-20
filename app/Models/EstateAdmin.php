<?php

namespace App\Models;

use App\Models\User;
use App\Trait\UseDisable;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstateAdmin extends Model
{
    use HasFactory;
    use UseDisable;

    protected $fillable = [
        'user_id',
        'estate_id',
        'is_owner',
        'status'
    ];

    protected $with = [
        'user'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)
            ->withDefault([
                'email' => 'N/A'
            ]);
    }

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }

    public function scopeAdmin($query)
    {
        return $query->where('is_owner', false);
    }

    public function scopeEstateOnly($query)
    {
        return $query->where('estate_id', auth()->user()->estate->first()->id);
    }
}
