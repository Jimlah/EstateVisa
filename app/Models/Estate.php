<?php

namespace App\Models;

use App\Trait\UseDisable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Estate extends Model
{
    use HasFactory;



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

}
