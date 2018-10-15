<?php

namespace App\Payslip;

final class EarningsItem implements ItemInterface
{
    /** @var string */
    private $shiftType;

    /** @var float */
    private $hours;

    /** @var float */
    private $rate;

    public function __construct(string $shiftType, float $hours, float $rate)
    {
        $this->shiftType = $shiftType;
        $this->hours = $hours;
        $this->rate = $rate;
    }

    public function getShiftType(): string
    {
        return $this->shiftType;
    }

    public function getHours(): float
    {
        return $this->hours;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getTotal(): float
    {
        return $this->hours * $this->rate;
    }
}
