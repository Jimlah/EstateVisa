<?php

namespace App\Models;

use App\Trait\UseDisable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Estate extends Model
{
    use HasFactory, UseDisable;

    const ACTIVE = 0;
    const SUSPENDED = 1;
    const DEACTIVATED = 2;



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

    protected $dateFormat = 'Y-m-d';

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


    public function houseOwner()
    {
        return $this->hasManyThrough(UsersHouse::class, House::class);
    }

    public function disableEstate()
    {
        $this->disable();
        foreach ($this->houseOwner as $house) {
            $house->disableHouse();
        }
    }

    public function enableEstate()
    {
        $this->enable();
        foreach ($this->houseOwner as $house) {
            $house->enableHouse();
        }
    }


    public function suspendEstate()
    {
        $this->suspend();
        foreach ($this->houseOwner as $house) {
            $house->suspendHouse();
        }
    }

    public function activateEstate()
    {
        $this->activate();
        foreach ($this->houseOwner as $house) {
            $house->activateHouse();
        }
    }

    public function deactivateEstate()
    {
        $this->deactivated();
        foreach ($this->houseOwner as $house) {
            $house->deactivateHouse();
        }
    }


}