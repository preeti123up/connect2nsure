<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveReminderNotification extends Notification
{
    use Queueable;

    protected $leave;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Leave $leave
     * @return void
     */
    public function __construct($leave)
    {
        $this->leave = $leave;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; // or other channels like 'database', 'sms', etc.
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Leave Reminder')
                    ->line('You have a pending leave request that needs your attention.')
                    ->line('Employee: ' . $this->leave->user->name) // Assumes Leave model has user relationship and User model has name attribute
                    ->line('Reason: ' . $this->leave->reason) // Assumes Leave model has a reason attribute
                    ->action('Review Leave Request', url('/account/leaves/pending-leaves/'))
                    ->line('Thank you for using our application!');
    }

    // Other methods for different channels can be added here
}
