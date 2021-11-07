<?php

namespace App\Models;

use App\Trait\UseDisable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Estate extends Model
{
    use HasFactory;
    use UseDisable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'address',
        'logo',
        'status'
    ];

    protected $dateFormat = 'Y-m-d';

    protected $with = ['admins', 'admins.user', 'admins.user.profile'];


    public function admins()
    {
        return $this->hasMany(EstateAdmin::class, 'estate_id');
    }

    public function owner()
    {
        return $this->hasOne(EstateAdmin::class, 'estate_id')->where('is_owner', true);
    }


    public function houseTypes()
    {
        return $this->hasMany(HouseType::class, 'estate_id');
    }

    public function houses()
    {
        return $this->hasMany(House::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }
}
