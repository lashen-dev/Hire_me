<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobApplication extends Notification
{
    use Queueable;

    private $job_title;
    private $applicant_name;
    private $application_id;

    public function __construct($job_title, $applicant_name, $application_id)
    {
        $this->job_title = $job_title;
        $this->applicant_name = $applicant_name;
        $this->application_id = $application_id;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'تقديم جديد',
            'body'  => "قام {$this->applicant_name} بالتقديم على وظيفة {$this->job_title}",
            'type'  => 'application_received',
            'id'    => $this->application_id, 
            'created_at' => now(),
        ];
    }
}