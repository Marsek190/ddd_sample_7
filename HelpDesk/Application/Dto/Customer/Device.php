<?php

namespace Dyson\Modules\HelpDesk\Application\Dto\Customer;

class Device
{
    public $id;
    public $title;
    public $serial;

    /**
     * @param int $id
     * @param string $title
     * @param string $serial
     */
    public function __construct($id, $title, $serial)
    {
        $this->id = $id;
        $this->title = $title;
        $this->serial = $serial;
    }
}
