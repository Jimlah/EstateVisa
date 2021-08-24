<?php

namespace App\Models;

use App\Trait\FilterByEstateTrait;
use App\Trait\UseDisable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory, FilterByEstateTrait;
    use UseDisable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'estate_id',
        'houses_types_id',
        'code',
        'description'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d'
    ];


    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }

    public function houseType()
    {
        return $this->belongsTo(House_type::class, 'houses_types_id');
    }

    public function disableHouse()
    {
        $this->disable();
    }

    public function enableHouse()
    {
        $this->enable();
    }
}