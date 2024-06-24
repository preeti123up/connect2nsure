<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\EmailNotificationSetting;
use Illuminate\Support\HtmlString;
use App\Models\User;
use App\Models\CelebrationType;
use App\Models\CelebratedEvent;
use App\Models\Wishes;


class MarriageAnniversaryNotification extends BaseNotification
{
    private $todayMarriageAnniversary;
    private $emailSetting;
    private $settings;

    /**
     * Create a new notification instance.
     */
    public function __construct($event)
    {
        $this->todayMarriageAnniversary = $event->todayMarriageAnniversary;
        $this->company = $event->company;
        $this->emailSetting = EmailNotificationSetting::where('company_id', $this->company->id)->where('slug', 'birthday-notification')->first();
        $this->settings =  Wishes::join('celebration_types','wishes_setting.type','celebration_types.id')
        ->where('company_id', $this->company->id)
        ->where('celebration_types.celebration_type',"marriage_anniversary")
        ->inRandomOrder()
        ->select('celebration_types.celebration_type','wishes_setting.*')
        ->first();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via( $notifiable): array
    {
        $CelebratedEvent = new CelebratedEvent();
        $CelebratedEvent->celebration_type_id = $this->settings->id;
        $CelebratedEvent->user_id = $notifiable->id;
        $CelebratedEvent->event_type = $this->settings->celebration_type;
        $CelebratedEvent->save();
        $via = array('database');
        if ($this->emailSetting->send_email == 'yes' && $notifiable->email_notifications && $notifiable->email !='') {
            array_push($via, 'mail');
        }

        if ($this->emailSetting->send_slack == 'yes' && $this->company->slackSetting->status == 'active' && $notifiable->employeeDetail->slack_username != '') {
            array_push($via, 'slack');
        }


        return $via;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail( $notifiable): MailMessage
    {
       
        $build = parent::build();
        $ccEmails = User::allEmployees($notifiable->id, false, null, $this->company->id)->pluck('email');
        $data['company'] = $this->company;
        $data['user'] = $notifiable;
        $data['settings'] = $this->settings;
        $data['details']= $this->todayMarriageAnniversary;

        return $build
            ->subject( __('Happy Marriage Anniversary '. $notifiable->name))
            ->cc($ccEmails)
            ->replyTo($notifiable->email, $notifiable->name)
            ->markdown('mail.celebration.email', $data);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {       
         return $this->todayMarriageAnniversary;

    }
    public function toSlack($notifiable) // phpcs:ignore
    {
        $new = new SlackMessage;

        $slack = $notifiable->company->slackSetting;

        $name = '';


            $name .=  $notifiable->name . "\n";

        return $new
            ->from(config('app.name'))
            ->to('@' . $notifiable->employeeDetail->slack_username)
            ->image($slack->slack_logo_url)
            ->content('>*' . __('email.MarriageAnniversary.text') . ' :name: *' . "\n" . $name . ' ');

    }
}
