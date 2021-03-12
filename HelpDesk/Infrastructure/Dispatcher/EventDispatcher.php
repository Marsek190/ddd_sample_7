<?php

namespace Dyson\Modules\HelpDesk\Infrastructure\Dispatcher;

use Dyson\Modules\HelpDesk\Application\Listener\LoggerEventListener;
use Dyson\Modules\HelpDesk\Domain\Event\AbstractEvent;
use Dyson\Modules\HelpDesk\Domain\Event\RemoveCustomerDevice;
use Dyson\Modules\HelpDesk\Domain\Service\EventDispatcherInterface;

class EventDispatcher implements EventDispatcherInterface
{
    private $loggerEventListener;

    public function __construct(LoggerEventListener $loggerEventListener)
    {
        $this->loggerEventListener = $loggerEventListener;
    }

    /** @inheritDoc */
    public function dispatch(AbstractEvent $event)
    {
        if ($event instanceof RemoveCustomerDevice) {
            $this->loggerEventListener->onRemoveCustomerDevice($event);
        }
    }
}
