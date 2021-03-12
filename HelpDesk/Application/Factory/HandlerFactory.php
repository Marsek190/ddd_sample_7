<?php

namespace Dyson\Modules\HelpDesk\Application\Factory;

use Dyson\Base\Db\Connection;
use Dyson\Modules\HelpDesk\Application\Handler\SearchCustomerDevicesHandler;
use Dyson\Modules\HelpDesk\Application\Listener\LoggerEventListener;
use Dyson\Modules\HelpDesk\Application\Validation\CustomerPhoneNumberValidator;
use Dyson\Modules\HelpDesk\Domain\Handler\RemoveCustomerDeviceHandler;
use Dyson\Modules\HelpDesk\Infrastructure\Dispatcher\EventDispatcher;
use Dyson\Modules\HelpDesk\Infrastructure\Hydrator;
use Dyson\Modules\HelpDesk\Infrastructure\Provider\GetCustomerProvider;
use Dyson\Modules\HelpDesk\Infrastructure\Repository\CustomerRepository;
use Dyson\Modules\SharedKernel\Infrastructure\Helper\QueryBuilderHelper;

class HandlerFactory
{
    private $loggerFactory;
    private $customerRepo;
    private $customerProvider;

    public function __construct()
    {
        $this->loggerFactory = new LoggerFactory();
        $this->customerRepo = new CustomerRepository(
            new Connection(),
            new QueryBuilderHelper(),
            new Hydrator()
        );
        $this->customerProvider = new GetCustomerProvider(
            new Connection()
        );
    }

    /** @return RemoveCustomerDeviceHandler */
    public function createRemoveCustomerDeviceHandler()
    {
        $dispatcher = new EventDispatcher(new LoggerEventListener($this->loggerFactory->getLogger()));

        return new RemoveCustomerDeviceHandler(
            $dispatcher,
            $this->customerRepo
        );
    }

    /** @return SearchCustomerDevicesHandler */
    public function createSearchCustomerDevicesHandler()
    {
       return new SearchCustomerDevicesHandler(
           $this->customerProvider,
            new CustomerPhoneNumberValidator()
        );
    }
}
