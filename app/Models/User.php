<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasApiTokens;

    const SUPER_ADMIN = 'super_admin';
    const ADMIN = 'admin';
    const ESTATE_OWNER = 'estate_owner';
    const ESTATE_ADMIN = 'estate_admin';
    const HOUSE_OWNER = 'house_owner';
    const HOUSE_SUB_OWNER = 'house_sub_owner';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d'
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function estates()
    {
        return $this->hasOne(Estate::class);
    }

    public function estateAdmin()
    {
        return $this->hasMany(EstateUser::class);
    }

    public function houseOwner()
    {
        return $this->hasMany(UsersHouse::class);
    }

    public function houseSubOwner()
    {
        return $this->hasMany(HouseSubUser::class, 'house_owner_id');
    }

    public function hasRole($role)
    {
        switch ($role) {
            case self::SUPER_ADMIN:
                return $this->id == 1;
                break;
            case self::ADMIN:
                return $this->role_id == 2;
                break;
            case self::ESTATE_OWNER:
                return $this->estates?->user_id != null;
                break;
            case self::ESTATE_ADMIN:
                $collection = collect($this->estateAdmin());
                return $collection->contains('user_id', auth()->user()->id);
                break;
            default:
                return false;
                break;
        }
    }
}
