<?php

namespace Dyson\Modules\HelpDesk\Application\Dto\Customer;

class Customer
{
    public $id;
    public $phone;
    public $personal;
    public $devices;

    /**
     * @param int $id
     * @param string $phone
     * @param PersonalData $personal
     * @param Device[] $devices
     */
    public function __construct($id, $phone, PersonalData $personal, array $devices)
    {
        $this->id = $id;
        $this->phone = $phone;
        $this->personal = $personal;
        $this->devices = $devices;
    }
}
