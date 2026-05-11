<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Inquiry;

class InquiryStatusUpdatedNotification extends Notification
{
    use Queueable;

    protected $inquiry;

    public function __construct(Inquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'subject' => $this->inquiry->subject,
            'status' => $this->inquiry->status,
            'inquiry_id' => $this->inquiry->id,
            'timestamp' => now()->format('Y-m-d H:i'),
        ];
    }
}
