<?php

namespace Dyson\Modules\HelpDesk\Infrastructure\Provider;

use PDO;
use Dyson\Base\Db\Connection;
use Dyson\Modules\HelpDesk\Application\Dto\Customer\Customer as CustomerDto;
use Dyson\Modules\HelpDesk\Application\Dto\Customer\Device as DeviceDto;
use Dyson\Modules\HelpDesk\Application\Dto\Customer\PersonalData as PersonalDataDto;
use Dyson\Modules\HelpDesk\Application\Handler\Dto\CustomerPhoneNumberDto;
use Dyson\Modules\HelpDesk\Application\Service\GetCustomerProviderInterface;
use Dyson\Modules\HelpDesk\Infrastructure\Provider\DbResult\CustomerDevice;

class GetCustomerProvider implements GetCustomerProviderInterface
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /** @inheritDoc */
    public function execute(CustomerPhoneNumberDto $phoneNumberDto)
    {
        $query = "
            SELECT
                e.ID AS id,
                e.NAME AS title,
                cd.SERIAL_NUMBER AS serial,
                u.ID AS userId,
                u.FNAME AS firstName,
                u.MNAME AS middleName,
                u.LNAME AS lastName,
                u.PHONE AS phone
            FROM D_PRODUCT_SERIALS cd
            INNER JOIN D_USERS u ON u.ID = cd.USER_ID AND u.PHONE = ?
            INNER JOIN b_iblock_element_prop_s35 sp ON CONVERT(sp.PROPERTY_4469 USING utf8) = SUBSTR(cd.SERIAL_NUMBER, 1, 3)
            INNER JOIN b_iblock_element e ON e.ID = sp.IBLOCK_ELEMENT_ID
            WHERE e.NAME NOT LIKE '%old price%'
        ";
        $queryMapping = [
            $phoneNumberDto->phone,
        ];

        $stmt = $this->connection->query($query, $queryMapping);
        $stmt->setFetchMode(PDO::FETCH_CLASS, CustomerDevice::class);

        /** @var CustomerDevice[] $customerDeviceDtos */
        $customerDeviceDtos = $stmt->fetchAll();

        if (empty($customerDeviceDtos)) {
            return null;
        }

        $personal = new PersonalDataDto(
            $customerDeviceDtos[0]->firstName,
            $customerDeviceDtos[0]->middleName,
            $customerDeviceDtos[0]->lastName
        );

        $devices = [];
        foreach ($customerDeviceDtos as $customerDeviceDto) {
            if (!isset($devices[$customerDeviceDto->id])) {
                $devices[$customerDeviceDto->id] = new DeviceDto(
                    $customerDeviceDto->id,
                    $customerDeviceDto->title,
                    $customerDeviceDto->serial
                );
            }
        }

        return new CustomerDto(
            $customerDeviceDtos[0]->userId,
            $customerDeviceDtos[0]->phone,
            $personal,
            $devices
        );
    }
}
