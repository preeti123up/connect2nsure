<?php

namespace App\Listeners;

use App\Events\MarriageAnniversaryEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Notifications\MarriageAnniversaryNotification;
use Notification;

class MarriageAnniversaryListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * 
     * Handle the event.
     */
    public function handle(MarriageAnniversaryEvent $event): void
    {
        foreach($event->todayMarriageAnniversary as $user)
      {
         $users = User::where('id', $user['id'] )->get();
         Notification::send($users, new MarriageAnniversaryNotification($event));
      }
    }
}
