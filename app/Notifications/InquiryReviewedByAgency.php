<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Inquiry;

class InquiryReviewedByAgency extends Notification
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
            'inquiry_id' => $this->inquiry->id,
            'subject' => $this->inquiry->subject,
            'status' => $this->inquiry->status,
            'by_agency' => Auth()->user()->user_Name,
            'time' => now()->format('d M Y h:i A'),
            'message' => 'Your inquiry has been reviewed and updated to "' . $this->inquiry->status . '".'
        ];
    }
}
