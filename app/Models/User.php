<?php

namespace App\Models;

use App\Models\House;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasApiTokens;

    const SUPER_ADMIN = 'super_admin';
    const ADMIN = 'admin';
    const ESTATE_SUPER_ADMIN = 'estate_super_admin';
    const ESTATE_ADMIN = 'estate_admin';
    const HOUSE_OWNER = 'house_owner';
    const HOUSE_MEMBER = 'house_member';


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

    protected $with = ['profile'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */


    public function profile()
    {
        return $this->hasOne(Profile::class)->withDefault([
            'firstname' => '',
            'lastname' => '',
            'phone_number' => '',
            'gender' => ''
        ]);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function estateAdmin()
    {
        return $this->hasMany(EstateAdmin::class);
    }

    public function estate()
    {
        return $this->belongsToMany(Estate::class, 'estate_admins', 'user_id', 'estate_id');
    }

    public function userHouses()
    {
        return $this->hasMany(UserHouse::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }

    public function houses()
    {
        return $this->belongsToMany(House::class, 'user_houses', 'user_id', 'house_id');
    }

    public function hasRole($role)
    {
        switch ($role) {
            case self::SUPER_ADMIN:
                return $this->admin?->id == 1;
                break;
            case self::ADMIN:
                return $this->admin?->id > 1 ? true : false;
            case self::ESTATE_SUPER_ADMIN:
                $collection = collect($this->estateAdmin);
                return $collection->where('is_owner', true)->contains('user_id', $this->id);
            case self::ESTATE_ADMIN:
                $collection = collect($this->estateAdmin);
                return $collection->where('is_owner', false)->contains('user_id', $this->id);
            case self::HOUSE_OWNER:
                $collection = collect($this->userHouses->where('is_owner', true));
                return $collection->contains('user_id', $this->id);
            case self::HOUSE_MEMBER:
                $collection = collect($this->houses->where('is_owner', false));
                return $collection->contains('user_id', $this->id);
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
            self::HOUSE_MEMBER => $this->hasRole(self::HOUSE_MEMBER),
        ];
    }
}
