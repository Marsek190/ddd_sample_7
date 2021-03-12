<?php

namespace Dyson\Modules\HelpDesk\Domain\Service;

use Dyson\Modules\HelpDesk\Domain\Event\AbstractEvent;

interface EventDispatcherInterface
{
    /**
     * @param AbstractEvent $event
     * @return void
     */
    public function dispatch(AbstractEvent $event);
}
