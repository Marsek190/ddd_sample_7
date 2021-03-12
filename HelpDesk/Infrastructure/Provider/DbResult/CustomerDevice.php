<?php

namespace Dyson\Modules\HelpDesk\Infrastructure\Provider\DbResult;

// sql sqchema
class CustomerDevice
{
    // device data
    public $id;
    public $serial;
    public $title;

    // customer data
    public $userId;
    public $firstName;
    public $middleName;
    public $lastName;
    public $phone;
}
