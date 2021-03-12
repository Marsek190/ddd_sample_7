<?php

namespace Dyson\Modules\HelpDesk\Domain\Handler;

use Dyson\Modules\HelpDesk\Domain\Event\RemoveCustomerDevice;
use Dyson\Modules\HelpDesk\Domain\Exception\DeviceNotFoundException;
use Dyson\Modules\HelpDesk\Domain\Exception\CustomerNotFoundException;
use Dyson\Modules\HelpDesk\Domain\Handler\Dto\CustomerDeviceDto;
use Dyson\Modules\HelpDesk\Domain\Service\CustomerRepositoryInterface;
use Dyson\Modules\HelpDesk\Domain\Service\EventDispatcherInterface;

class RemoveCustomerDeviceHandler
{
    private $dispatcher;
    private $customerRepo;

    public function __construct(EventDispatcherInterface $dispatcher, CustomerRepositoryInterface $customerRepo)
    {
        $this->dispatcher = $dispatcher;
        $this->customerRepo = $customerRepo;
    }

    /**
     * @param CustomerDeviceDto $customerDeviceDto
     * @return void
     * @throws DeviceNotFoundException
     * @throws CustomerNotFoundException
     */
    public function handle(CustomerDeviceDto $customerDeviceDto)
    {
        $customer = $this->customerRepo->findById($customerDeviceDto->userId);
        $customer->removeDevice($customerDeviceDto->deviceId);
        $this->customerRepo->save($customer);
        $removeCustomerDeviceEvent = new RemoveCustomerDevice(
            $customerDeviceDto->userId,
            $customerDeviceDto->deviceId
        );
        $this->dispatcher->dispatch($removeCustomerDeviceEvent);
    }
}
