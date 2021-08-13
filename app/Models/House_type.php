<?php

namespace App\Models;

use App\Models\Estate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class House_type extends Model
{
    use HasFactory;

    protected $fillable = [
        'estate_id',
        'name',
        'description',
        'code'
    ];

    protected static function booted()
    {
        static::addGlobalScope(
            'Estate_House_Type',
            function (Builder $builder) {
                if (auth()->user()->hasRole(User::ESTATE_OWNER)) {
                    $builder->where(
                        'estate_id',
                        '=',
                        auth()->user()->estates?->id
                    );
                }
            }
        );
    }

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }

    public function house()
    {
        $this->belongsTo(House::class);
    }
}