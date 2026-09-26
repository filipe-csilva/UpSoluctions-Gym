<?php

namespace App\Notifications;

use App\Models\PhysicalAssessment;
use App\Notifications\Concerns\HasOperationalChannels;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PhysicalAssessmentCreated extends Notification implements ShouldQueue
{
    use HasOperationalChannels, Queueable;

    public function __construct(public PhysicalAssessment $assessment) {}

    public function via(object $notifiable): array
    {
        return $this->operationalChannels();
    }

    public function toDatabase(object $notifiable): array
    {
        return ['type' => 'physical_assessment_created', 'title' => 'Nova avaliação física', 'message' => 'Uma nova avaliação física foi registrada.', 'url' => route('assessments.mine')];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->subject('Nova avaliação física')->line($this->toDatabase($notifiable)['message'])->action('Ver avaliação', route('assessments.mine'));
    }
}
