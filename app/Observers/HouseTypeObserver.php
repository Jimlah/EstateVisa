<?php

namespace App\Observers;

use App\Models\House_type;
use App\Models\User;

class HouseTypeObserver
{
    /**
     * Handle the House_type "created" event.
     *
     * @param  \App\Models\House_type  $house_type
     * @return void
     */
    public function created(House_type $house_type)
    {
        if (auth()->user()->hasRole(User::ESTATE_OWNER)) {
            $house_type->estate_id = auth()->user()->id;
        }
    }

    /**
     * Handle the House_type "updated" event.
     *
     * @param  \App\Models\House_type  $house_type
     * @return void
     */
    public function updated(House_type $house_type)
    {
        //
    }

    /**
     * Handle the House_type "deleted" event.
     *
     * @param  \App\Models\House_type  $house_type
     * @return void
     */
    public function deleted(House_type $house_type)
    {
        //
    }

    /**
     * Handle the House_type "restored" event.
     *
     * @param  \App\Models\House_type  $house_type
     * @return void
     */
    public function restored(House_type $house_type)
    {
        //
    }

    /**
     * Handle the House_type "force deleted" event.
     *
     * @param  \App\Models\House_type  $house_type
     * @return void
     */
    public function forceDeleted(House_type $house_type)
    {
        //
    }
}