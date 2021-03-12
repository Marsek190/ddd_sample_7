<?php

namespace Dyson\Modules\HelpDesk\Application\Listener;

use Psr\Log\LoggerInterface;
use Dyson\Modules\HelpDesk\Domain\Event\RemoveCustomerDevice;

class LoggerEventListener
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onRemoveCustomerDevice(RemoveCustomerDevice $event)
    {
        $this->logger->info(
            sprintf('У пользователя %s был удален товар %s.', $event->userId(), $event->deviceId())
        );
    }
}
