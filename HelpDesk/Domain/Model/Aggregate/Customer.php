<?php

namespace Dyson\Modules\HelpDesk\Domain\Model\Aggregate;

use Dyson\Modules\HelpDesk\Domain\Exception\DeviceNotFoundException;
use Dyson\Modules\HelpDesk\Domain\Model\ValueObject\Customer\Device;

class Customer
{
    private $id;
    private $devices;

    /**
     * @param int $id
     * @param Device[] $devices
     */
    public function __construct($id, array $devices)
    {
        $this->id = $id;
        $this->devices = array_reduce(
            array_values($devices),
            function (array $devicesMapId, Device $device) {
                $devicesMapId[$device->id()] = $device;
                return $devicesMapId;
            },
            []
        );
    }

    /** @return int */
    public function id()
    {
        return $this->id;
    }

    /** @return Device[] */
    public function devices()
    {
        return $this->devices;
    }

    /**
     * @param int $deviceId
     * @return void
     * @throws DeviceNotFoundException
     */
    public function removeDevice($deviceId)
    {
        if (!isset($this->devices[$deviceId])) {
            throw new DeviceNotFoundException();
        }

        unset($this->devices[$deviceId]);
    }

    /**
     * @param Device $device
     * @return void
     */
    public function addDevice(Device $device)
    {
        $this->devices[$device->id()] = $device;
    }
}
