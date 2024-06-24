<?php


namespace App\Notifications;

use App\Models\EmailNotificationSetting;


class ResetPasswordNotification extends BaseNotification
{
    private $otp;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
     
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
            $build = parent::build();
            $content = 'Your OTP (One-Time Password) for password reset is: ' . $this->otp;
    
            return $build
                ->subject('Reset Your Password - ' . config('app.name') . '.')
                ->markdown('mail.email', [
                    'content' => $content,
                    'themeColor' => "red",
                    'actionText' => __('email.shiftChange.action'),
                    'notifiableName' => $notifiable->name
                ]);
            
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
