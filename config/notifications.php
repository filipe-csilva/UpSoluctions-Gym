<?php

return [
    'mail_enabled' => (bool) env('NOTIFICATIONS_MAIL_ENABLED', false),
    'whatsapp_enabled' => (bool) env('NOTIFICATIONS_WHATSAPP_ENABLED', false),
    'push_enabled' => (bool) env('NOTIFICATIONS_PUSH_ENABLED', false),
];
