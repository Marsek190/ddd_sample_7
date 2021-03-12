<?php

namespace Dyson\Modules\HelpDesk\Application\Service;

use Dyson\Modules\HelpDesk\Application\Dto\Customer\Customer;
use Dyson\Modules\HelpDesk\Application\Handler\Dto\CustomerPhoneNumberDto;

interface GetCustomerProviderInterface
{
    /**
     * @param CustomerPhoneNumberDto $phoneNumberDto
     * @return Customer
     */
    public function execute(CustomerPhoneNumberDto $phoneNumberDto);
}
