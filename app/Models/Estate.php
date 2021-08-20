<?php

namespace App\Models;

use App\Trait\UseDisable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Estate extends Model
{
    use HasFactory, UseDisable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'code',
        'address',
        'logo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function houses()
    {
        return $this->hasMany(House::class);
    }

    public function houseTypes()
    {
        return $this->hasMany(House_type::class);
    }

    public function estateAdmin()
    {
        return $this->hasMany(EstateUser::class);
    }

    public function disableEstate()
    {
        $this->disable();
        foreach ($this->houses as $house) {
            $house->disableHouse();
        }
    }
}