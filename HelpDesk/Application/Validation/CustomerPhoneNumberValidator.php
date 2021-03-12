<?php

namespace Dyson\Modules\HelpDesk\Application\Validation;

use Dyson\Modules\HelpDesk\Application\Handler\Dto\CustomerPhoneNumberDto;
use Dyson\Modules\HelpDesk\Domain\Exception\BadPhoneNumberException;

class CustomerPhoneNumberValidator
{
    // номер приходит без символов `+` и `-`
    private $pattern = '/8\d{10}/';

    /**
     * @param CustomerPhoneNumberDto $phoneNumberDto
     * @return void
     * @throws BadPhoneNumberException
     */
    public function validate(CustomerPhoneNumberDto $phoneNumberDto)
    {
        if (
            empty($phoneNumberDto->phone) ||
            !is_string($phoneNumberDto->phone) ||
            !preg_match($this->pattern, $phoneNumberDto->phone)
        ) {
            throw new BadPhoneNumberException();
        }
    }
}
