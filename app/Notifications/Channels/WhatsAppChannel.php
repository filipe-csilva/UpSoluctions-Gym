<?php

namespace App\Notifications\Channels;

use Illuminate\Support\Facades\Http;

class WhatsAppChannel
{
    public function send(object $notifiable, object $notification): void
    {
        $url = config('services.whatsapp.url');
        $token = config('services.whatsapp.token');
        $phone = $notifiable->studentProfile?->phone ?? $notifiable->phone ?? null;
        if (! $url || ! $token || ! $phone || ! method_exists($notification, 'toWhatsApp')) {
            return;
        }

        Http::timeout(5)->withToken($token)->post($url, ['to' => $phone, ...$notification->toWhatsApp($notifiable)]);
    }
}
