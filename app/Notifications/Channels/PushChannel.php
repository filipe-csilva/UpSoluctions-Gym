<?php

namespace App\Notifications\Channels;

use Illuminate\Support\Facades\Http;

class PushChannel
{
    public function send(object $notifiable, object $notification): void
    {
        $url = config('services.push.url');
        $token = config('services.push.token');
        if (! $url || ! $token || ! method_exists($notification, 'toPush')) {
            return;
        }

        Http::timeout(5)->withToken($token)->post($url, ['user_id' => $notifiable->getKey(), ...$notification->toPush($notifiable)]);
    }
}
