<?php

namespace App\Trait;

use App\Models\User;

trait UseDisable
{

    public function suspend()
    {
        $this->update(['status' => User::SUSPENDED]);
    }

    public function activate()
    {
        $this->update(['status' => User::ACTIVE]);
    }

    public function deactivate()
    {
        $this->update(['status' => User::DEACTIVATED]);
    }

    public function getStatusAttribute()
    {
        switch ($this->attributes['status']) {
            case User::ACTIVE:
                return 'Active';
                break;
            case User::SUSPENDED:
                return 'Suspended';
                break;
            case User::DEACTIVATED:
                return 'Suspended';
                break;
            default:
                return 'Unknown';
                break;
        }
    }
}
