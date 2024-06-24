<?php

namespace App\Listeners;

use App\Events\BirthdayWishesEvent;
use App\Notifications\BirthdayWishes;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Notification;
use App\Models\User;
class BirthdayWishesListener
{
   
    /**
     * Handle the event.
     */
 
      public function handle(BirthdayWishesEvent $event)
    {
        
        foreach($event->todayBirthdayWish as $user)
        {
           $users = User::where('id', $user['id'] )->get();
           Notification::send($users, new BirthdayWishes($event));
        }

    }
}
