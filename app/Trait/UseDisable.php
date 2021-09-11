<?php
namespace App\Trait;

use App\Models\User;

trait UseDisable
{
    public function disable()
    {
        $this->status = false;
        $this->save();
    }

    public function enable()
    {
        $this->status = true;
        $this->save();
    }

    public function suspended()
    {
        $this->status = User::SUSPENDED;
        $this->save();
    }

    public function activate()
    {
        $this->status = User::ACTIVE;
        $this->save();
    }

    public function deactivated()
    {
        $this->status = User::DEACTIVATED;
        $this->save();
    }
}