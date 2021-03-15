<?php

namespace Dyson\Modules\HelpDesk\Infrastructure\Repository;

use PDO;
use Dyson\Base\Db\Connection;
use Dyson\Modules\HelpDesk\Domain\Model\Aggregate\Customer;
use Dyson\Modules\HelpDesk\Domain\Model\ValueObject\Customer\Device;
use Dyson\Modules\HelpDesk\Domain\Service\CustomerRepositoryInterface;
use Dyson\Modules\HelpDesk\Domain\Exception\CustomerNotFoundException;
use Dyson\Modules\HelpDesk\Domain\Exception\DataAccessException;
use Dyson\Modules\HelpDesk\Infrastructure\Hydrator;
use Dyson\Modules\HelpDesk\Infrastructure\Repository\DbResult\CustomerDevice;
use Dyson\Modules\SharedKernel\Infrastructure\Helper\QueryBuilderHelper;

class CustomerRepository implements CustomerRepositoryInterface
{
    private $connection;
    private $queryBuilderHelper;
    private $hydrator;

    public function __construct(
        Connection $connection,
        QueryBuilderHelper $queryBuilderHelper,
        Hydrator $hydrator
    ) {
        $this->connection = $connection;
        $this->queryBuilderHelper = $queryBuilderHelper;
        $this->hydrator = $hydrator;
    }

    /** @inheritDoc */
    public function findById($id)
    {
        $query = "
            SELECT
                e.ID AS id,
                e.NAME AS title,
                cd.SERIAL_NUMBER AS serial,
                cd.USER_ID AS userId
            FROM D_PRODUCT_SERIALS cd
            INNER JOIN b_iblock_element_prop_s35 sp ON CONVERT(sp.PROPERTY_4469 USING utf8) = SUBSTR(cd.SERIAL_NUMBER, 1, 3)
            INNER JOIN b_iblock_element e ON e.ID = sp.IBLOCK_ELEMENT_ID
            WHERE cd.USER_ID = ? AND e.NAME NOT LIKE '%old price%'
        ";
        $queryMapping = [
            $id,
        ];

        $stmt = $this->connection->query($query, $queryMapping);
        $stmt->setFetchMode(PDO::FETCH_CLASS, CustomerDevice::class);

        /** @var CustomerDevice[] $customerDeviceDtos */
        $customerDeviceDtos = $stmt->fetchAll();

        if (empty($customerDeviceDtos)) {
            throw new CustomerNotFoundException();
        }

        $devices = [];
        foreach ($customerDeviceDtos as $customerDeviceDto) {
            $devices[$customerDeviceDto->id] = $this->hydrator->hydrate(Device::class, [
                'id' => (int) $customerDeviceDto->id,
                'title' => $customerDeviceDto->title,
                'serial' => $customerDeviceDto->serial,
            ]);
        }

        return $this->hydrator->hydrate(Customer::class, [
            'id' => (int) $customerDeviceDtos[0]->userId,
            'devices' => $devices,
        ]);
    }

    /** @inheritDoc */
    public function save(Customer $customer)
    {
        if (empty($customer->devices()) {
            $query = 'DELETE FROM D_PRODUCT_SERIALS WHERE USER_ID = ?';
            $queryMapping = [
                $customer->id(),
            ];
            if (!$this->connection->execute($query, $queryMapping)) {
                throw new DataAccessException();
            }

            return true;
        }

        $deviceSerials = array_map(function (Device $device) {
            return $device->serial();
        }, array_values($customer->devices()));

        $query = '
            DELETE FROM D_PRODUCT_SERIALS
            WHERE (
                SERIAL_NUMBER NOT IN (' . $this->queryBuilderHelper->getPlaceholdersForArray($deviceSerials) . ')
                AND USER_ID = ?
            )
        ';
        $queryMapping = array_merge($deviceSerials, [
            $customer->id(),
        ]);

        if (!$this->connection->execute($query, $queryMapping)) {
            throw new DataAccessException();
        }
        
        return true;
    }
}
