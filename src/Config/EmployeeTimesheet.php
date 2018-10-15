<?php

namespace App\Config;

class EmployeeTimesheet
{
    /** @var SuperFund[] */
    private $superFunds;

    /** @var Shift[] */
    private $shifts;

    public function __construct(array $superFunds, array $shifts)
    {
        $this->superFunds = $superFunds;
        $this->shifts = $shifts;
    }

    public function getSuperFunds(): array
    {
        return $this->superFunds;
    }

    public function getShifts(): array
    {
        return $this->shifts;
    }
}
