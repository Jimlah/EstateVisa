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
        'logo'
    ];

    protected $dateFormat = 'Y-m-d';


    public function admins()
    {
        return $this->hasMany(EstateAdmin::class, 'estate_id');
    }


    public function houseTypes()
    {
        return $this->hasMany(HouseType::class, 'estate_id');
    }

    public function houses()
    {
        return $this->hasMany(House::class);
    }
}
