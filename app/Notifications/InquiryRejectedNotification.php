<?php

namespace App\Notifications;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InquiryRejectedNotification extends Notification
{
    use Queueable;

    public $inquiry;

    public function __construct(Inquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Inquiry Rejected by Agency')
            ->greeting('Hello MCMC Officer,')
            ->line('An inquiry has been rejected by an agency.')
            ->line('Subject: ' . $this->inquiry->subject)
            ->line('Rejection Reason: ' . $this->inquiry->investigation_status)
            ->action('View Inquiry', url('/mcmc/inquiries/' . $this->inquiry->id))
            ->line('Please reassign or take further action.');
    }
}
