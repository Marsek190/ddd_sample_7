<?php

namespace Dyson\Modules\HelpDesk\Application\Handler;

use Dyson\Modules\HelpDesk\Application\Service\GetCustomerProviderInterface;
use Dyson\Modules\HelpDesk\Application\Dto\Customer\Customer as CustomerDto;
use Dyson\Modules\HelpDesk\Application\Handler\Dto\CustomerPhoneNumberDto;
use Dyson\Modules\HelpDesk\Application\Validation\CustomerPhoneNumberValidator;
use Dyson\Modules\HelpDesk\Domain\Exception\BadPhoneNumberException;

class SearchCustomerDevicesHandler
{
    private $customerProvider;
    private $validator;

    public function __construct(GetCustomerProviderInterface $customerProvider, CustomerPhoneNumberValidator $validator)
    {
        $this->customerProvider = $customerProvider;
        $this->validator = $validator;
    }

    /**
     * @param CustomerPhoneNumberDto $phoneNumberDto
     * @return CustomerDto|null
     * @throws BadPhoneNumberException
     */
    public function handle(CustomerPhoneNumberDto $phoneNumberDto)
    {
        $this->validator->validate($phoneNumberDto);

        return $this->customerProvider->execute($phoneNumberDto);
    }
}
