<?php

namespace App\Models;

use App\Trait\UseDisable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Estate extends Model
{
    use HasFactory;


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

    // public function admin()
    // {
    //     return $this->hasMany(EstateAdmin::class, 'estate_id');
    // }

    public function user()
    {
        return $this->belongsToMany(User::class, 'estate_admins', 'estate_id', 'user_id')->withPivot('status', 'role', 'created_at');
    }
}
