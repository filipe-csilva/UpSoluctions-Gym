<?php

namespace App\Console\Commands;

use App\Jobs\DispatchOperationalNotifications;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('notifications:dispatch-operational')]
#[Description('Envia notificações operacionais de financeiro e matrículas.')]
class DispatchOperationalNotificationsCommand extends Command
{
    public function handle(): int
    {
        DispatchOperationalNotifications::dispatch();
        $this->info('Job de notificações enviado para a fila.');

        return self::SUCCESS;
    }
}
