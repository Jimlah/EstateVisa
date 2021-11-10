<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Visitor;
use App\Notifications\GatePassIssued;
use App\Notifications\GatePassRequest;
use Illuminate\Support\Facades\Notification;

class VisitorObserver
{
    /**
     * Handle the Visitor "created" event.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return void
     */
    public function created(Visitor $visitor)
    {
        if ($visitor->sent_by == User::class) {
            $visitor->estate->admins->each(function ($admin) use ($visitor) {
                $admin->user->notify(new GatePassIssued($visitor));
            });
        } else {
            $visitor->user->notify(new GatePassRequest($visitor));
        }
    }

    /**
     * Handle the Visitor "updated" event.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return void
     */
    public function updated(Visitor $visitor)
    {
        //
    }

    /**
     * Handle the Visitor "deleted" event.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return void
     */
    public function deleted(Visitor $visitor)
    {
        //
    }

    /**
     * Handle the Visitor "restored" event.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return void
     */
    public function restored(Visitor $visitor)
    {
        //
    }

    /**
     * Handle the Visitor "force deleted" event.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return void
     */
    public function forceDeleted(Visitor $visitor)
    {
        //
    }
}
