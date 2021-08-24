<?php

namespace App\Observers;

use App\Models\House;

class HouseObserver
{
    /**
     * Handle the House "created" event.
     *
     * @param  \App\Models\House  $house
     * @return void
     */
    public function created(House $house)
    {

        $house->estate_id = auth()->user()?->estate->id;
        $house->save();
    }

    /**
     * Handle the House "updated" event.
     *
     * @param  \App\Models\House  $house
     * @return void
     */
    public function updated(House $house)
    {
        //
    }

    /**
     * Handle the House "deleted" event.
     *
     * @param  \App\Models\House  $house
     * @return void
     */
    public function deleted(House $house)
    {
        //
    }

    /**
     * Handle the House "restored" event.
     *
     * @param  \App\Models\House  $house
     * @return void
     */
    public function restored(House $house)
    {
        //
    }

    /**
     * Handle the House "force deleted" event.
     *
     * @param  \App\Models\House  $house
     * @return void
     */
    public function forceDeleted(House $house)
    {
        //
    }
}
