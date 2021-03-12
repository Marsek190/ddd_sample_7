<?php

namespace Dyson\Modules\HelpDesk\Domain\Service;

use Dyson\Modules\HelpDesk\Domain\Exception\CustomerNotFoundException;
use Dyson\Modules\HelpDesk\Domain\Exception\DataAccessException;
use Dyson\Modules\HelpDesk\Domain\Model\Aggregate\Customer;

interface CustomerRepositoryInterface
{
    /**
     * @param int $id
     * @return Customer
     * @throws CustomerNotFoundException
     */
    public function findById($id);

    /**
     * @param Customer $customer
     * @return void
     * @throws DataAccessException
     */
    public function save(Customer $customer);
}
