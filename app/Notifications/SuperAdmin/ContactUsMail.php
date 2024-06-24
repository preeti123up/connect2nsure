<?php

namespace App\Notifications\SuperAdmin;

use App\Notifications\BaseNotification;

class ContactUsMail extends BaseNotification
{


    public $data;
    public $pdfPath;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data,$pdfPath)
    {
        $this->data = $data;
        $this->pdfPath = $pdfPath;
    }

    public function via()
    {
        $via = ['mail'];
        return $via;
    }

    public function toMail()
    {
        return parent::build()
            ->subject('Contact Us' . ' ' . config('app.name') . '!')
            ->markdown('vendor.notifications.superadmin.enquiry-us', $this->data)
            ->attach($this->pdfPath);

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $notifiable->toArray();
    }

}
