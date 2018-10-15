<?php

namespace App\Config;

class PayConfig
{
    /** @var float */
    private $baseRate;

    /** @var float */
    private $regularHours;

    /** @var float */
    private $overtimeMultiplier;

    /** @var array */
    private $shiftTypes;

    /** @var array */
    private $taxTypes;

    public function __construct(
        float $baseRate,
        float $regularHours,
        float $overtimeMultiplier,
        array $shiftTypes,
        array $taxTypes
    ) {
        $this->baseRate           = $baseRate;
        $this->regularHours       = $regularHours;
        $this->overtimeMultiplier = $overtimeMultiplier;
        $this->shiftTypes         = $shiftTypes;
        $this->taxTypes           = $taxTypes;
    }

    public function getBaseRate(): float
    {
        return $this->baseRate;
    }

    public function getRegularHours(): float
    {
        return $this->regularHours;
    }

    public function getOvertimeMultiplier(): float
    {
        return $this->overtimeMultiplier;
    }

    public function getShiftTypes(): array
    {
        return $this->shiftTypes;
    }

    public function getTaxTypes(): array
    {
        return $this->taxTypes;
    }
}
