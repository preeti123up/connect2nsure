<?php

namespace App\Listeners;

use App\Events\LeaveReminderEvent;
use App\Notifications\LeaveReminderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class LeaveReminderListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param LeaveReminderEvent $event
     * @return void
     */
    public function handle(LeaveReminderEvent $event)
    {
      
        Notification::send($event->reportingManager, new LeaveReminderNotification($event->leave));
    }
}
