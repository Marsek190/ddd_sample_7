<?php

namespace Dyson\Modules\HelpDesk\Application\Dto\Customer;

class PersonalData
{
    public $firstName;
    public $middleName;
    public $lastName;

    /**
     * @param string $firstName
     * @param string $middleName
     * @param string $lastName
     */
    public function __construct($firstName, $middleName, $lastName)
    {
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
    }
}
