<?php

namespace App\Notifications;

use App\Models\Enrollment;
use App\Notifications\Concerns\HasOperationalChannels;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnrollmentExpiring extends Notification implements ShouldQueue
{
    use HasOperationalChannels, Queueable;

    public function __construct(public Enrollment $enrollment) {}

    public function via(object $notifiable): array
    {
        return $this->operationalChannels();
    }

    public function toDatabase(object $notifiable): array
    {
        return ['type' => 'enrollment_expiring', 'title' => 'Matrícula próxima do vencimento', 'message' => 'Sua matrícula vence em '.$this->enrollment->end_date->format('d/m/Y').'.', 'url' => route('panel')];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->subject('Matrícula próxima do vencimento')->line($this->toDatabase($notifiable)['message'])->action('Acessar academia', route('panel'));
    }
}
