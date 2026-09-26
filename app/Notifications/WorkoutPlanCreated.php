<?php

namespace App\Notifications;

use App\Models\WorkoutPlan;
use App\Notifications\Concerns\HasOperationalChannels;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkoutPlanCreated extends Notification implements ShouldQueue
{
    use HasOperationalChannels, Queueable;

    public function __construct(public WorkoutPlan $plan) {}

    public function via(object $notifiable): array
    {
        return $this->operationalChannels();
    }

    public function toDatabase(object $notifiable): array
    {
        return ['type' => 'workout_plan_created', 'title' => 'Nova ficha de treino', 'message' => 'Sua ficha '.$this->plan->name.' foi criada.', 'url' => route('workout-plans.show', $this->plan)];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->subject('Nova ficha de treino')->line($this->toDatabase($notifiable)['message'])->action('Ver ficha', route('workout-plans.show', $this->plan));
    }
}
