<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\WorkAnniversaryEvent;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\WorkAnniversary;
use App\Models\User;

use Notification;

class WorkAnniversaryListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WorkAnniversaryEvent $event): void
    {
        foreach($event->todayWorkAnniversary as $user)
        {
           $users = User::where('id', $user['id'] )->get();
           Notification::send($users, new WorkAnniversary($event));
        }
    }
}
