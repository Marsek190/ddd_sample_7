<?php

namespace Dyson\Modules\HelpDesk\Domain\Event;

class RemoveCustomerDevice extends AbstractEvent
{
    private $userId;
    private $deviceId;

    /**
     * @param int $userId
     * @param int $deviceId
     */
    public function __construct($userId, $deviceId)
    {
        $this->deviceId = $deviceId;
        $this->userId = $userId;
    }

    /** @return int */
    public function userId()
    {
        return $this->userId;
    }

    /** @return int */
    public function deviceId()
    {
        return $this->deviceId;
    }
}
