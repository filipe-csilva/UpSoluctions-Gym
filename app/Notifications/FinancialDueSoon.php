<?php

namespace App\Notifications;

use App\Models\FinancialTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FinancialDueSoon extends Notification
{
    use Queueable;

    public function __construct(public FinancialTransaction $transaction) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'financial_due_soon',
            'title' => 'Fatura próxima do vencimento',
            'message' => sprintf(
                'Sua fatura "%s" vence em 5 dias.',
                $this->transaction->description,
            ),
            'transaction_id' => $this->transaction->id,
            'url' => route('panel'),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
