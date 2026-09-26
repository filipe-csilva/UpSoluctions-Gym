<?php

namespace App\Notifications\Concerns;

use App\Notifications\Channels\PushChannel;
use App\Notifications\Channels\WhatsAppChannel;

trait HasOperationalChannels
{
    public function operationalChannels(): array
    {
        $channels = ['database'];
        if (config('notifications.mail_enabled')) {
            $channels[] = 'mail';
        }
        if (config('notifications.whatsapp_enabled')) {
            $channels[] = WhatsAppChannel::class;
        }
        if (config('notifications.push_enabled')) {
            $channels[] = PushChannel::class;
        }

        return $channels;
    }

    public function toWhatsApp(object $notifiable): array
    {
        return ['message' => $this->toDatabase($notifiable)['message']];
    }

    public function toPush(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
