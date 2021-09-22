<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    use HasApiTokens;

    const SUPER_ADMIN = 'super_admin';
    const ADMIN = 'admin';
    const ESTATE_SUPER_ADMIN = 'estate_super_admin';
    const ESTATE_ADMIN = 'estate_admin';
    const HOUSE_OWNER = 'house_owner';
    const HOUSE_SUB_OWNER = 'house_sub_owner';


    const ACTIVE = '1';
    const DEACTIVATED = '2';
    const SUSPENDED = '3';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function estate_admin()
    {
        return $this->hasOne(EstateAdmin::class);
    }


    public function hasRole($role)
    {
        switch ($role) {
            case self::SUPER_ADMIN:
                return $this->admin?->id == 1;
                break;
            case self::ADMIN:
                return $this->admin?->id > 1;
            default:
                return false;
        }
    }

    public function roles()
    {
        return [
            self::SUPER_ADMIN => $this->hasRole(self::SUPER_ADMIN),
            self::ADMIN => $this->hasRole(self::ADMIN),
            self::ESTATE_SUPER_ADMIN => $this->hasRole(self::ESTATE_SUPER_ADMIN),
            self::ESTATE_ADMIN => $this->hasRole(self::ESTATE_ADMIN),
            self::HOUSE_OWNER => $this->hasRole(self::HOUSE_OWNER),
        ];
    }
}
