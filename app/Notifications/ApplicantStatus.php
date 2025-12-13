<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicantStatus extends Notification implements ShouldQueue
{
    use Queueable;

    public $status;
    public $job_title;
    public function __construct($status, $job_title)
    {
        $this->status = $status;
        $this->job_title = $job_title;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database' , 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isAccepted = $this->status === 'accepted';
        
        return (new MailMessage)
            ->subject($isAccepted ? 'مبروك! تم قبولك 🎉' : 'تحديث بخصوص طلب التوظيف')
            ->greeting('مرحباً ' . $notifiable->name . '،')
            ->line($isAccepted 
                ? "يسعدنا إخبارك بأنه تم قبول طلبك لوظيفة {$this->job_title}." 
                : "نأسف لإبلاغك بأنه تم رفض طلبك لوظيفة {$this->job_title}.")
                ->line('شكراً لاستخدامك تطبيقنا للتوظيف.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        
        if ($this->status === 'accepted') {
            $message = "Congratulations! Your application for the position of '{$this->job_title}' has been accepted.";
        } elseif ($this->status === 'rejected') {
            $message = "We regret to inform you that your application for the position of '{$this->job_title}' has been rejected.";
        } else {
            $message = "Your application status for the position of '{$this->job_title}' has been updated to '{$this->status}'.";
        }
        return [
            "body" => $message
        ];
    }
}
