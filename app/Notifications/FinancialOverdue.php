<?php

namespace App\Notifications;

use App\Models\FinancialTransaction;
use App\Notifications\Concerns\HasOperationalChannels;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FinancialOverdue extends Notification implements ShouldQueue
{
    use HasOperationalChannels, Queueable;

    public function __construct(public FinancialTransaction $transaction) {}

    public function via(object $notifiable): array
    {
        return $this->operationalChannels();
    }

    public function toDatabase(object $notifiable): array
    {
        return ['type' => 'financial_overdue', 'title' => 'Mensalidade vencida', 'message' => 'A mensalidade '.$this->transaction->description.' está vencida.', 'url' => route('student-financial.show', $this->transaction)];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->subject('Mensalidade vencida')->line($this->toDatabase($notifiable)['message'])->action('Ver financeiro', route('student-financial.index'));
    }
}
