<?php

namespace Dyson\Modules\HelpDesk\Domain\Model\ValueObject\Customer;

class Device
{
    private $id;
    private $title;
    private $serial;

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

    /** @return int */
    public function id()
    {
        return $this->id;
    }

    /** @return string */
    public function title()
    {
        return $this->title;
    }

    /** @return string */
    public function serial()
    {
        return $this->serial;
    }
}
