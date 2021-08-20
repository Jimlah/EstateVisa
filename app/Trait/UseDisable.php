<?php
namespace App\Trait;

trait UseDisable
{
    public function disable()
    {
        $this->status = false;
        $this->save();
    }
}