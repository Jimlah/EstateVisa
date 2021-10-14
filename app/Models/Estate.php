<?php

namespace App\Models;

use App\Trait\UseDisable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Estate extends Model
{
    use HasFactory;
    use UseDisable;


    protected $with = ['user'];

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

    // Check for bugs
    public function admin()
    {
        return $this->belongsToMany(User::class, 'estate_admins', 'estate_id', 'user_id')
            ->where('estate_admins.role', '=', User::ESTATE_ADMIN)->withPivot('role', 'estate_id', 'user_id');
    }

    public function estate_admin()
    {
        return $this->hasMany(EstateAdmin::class, 'estate_id');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'estate_admins', 'estate_id', 'user_id')->withPivot('status', 'role', 'created_at');
    }

    public function estateSuperAdmin()
    {
        return $this->hasMany(EstateAdmin::class, 'estate_id')
            ->where('estate_admins.role', '=', User::ESTATE_SUPER_ADMIN);
    }

    public function houseTypes()
    {
        return $this->hasMany(HouseType::class, 'estate_id');
    }

    public function houses()
    {
        return $this->belongsToMany(House::class, 'estate_houses', 'estate_id', 'house_id');
    }

    public function houseOwners()
    {
        return $this->hasMany(HouseOwner::class, 'estate_id');
    }
}
