<?php

namespace App\Models;

use App\Models\User;
use App\Trait\FilterByUserTrait;
use App\Trait\UseDisable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsersHouse extends Model
{
    use HasFactory;
    use UseDisable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'house_id',
    ];

    public function scopeEstate($query)
    {
        return $query->where('house', 1);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function suspendHouse()
    {
        $this->suspended();
    }

    public function activateHouse()
    {
        $this->active();
    }

    public function deactivateHouse()
    {
        $this->deactivated();
    }


}
