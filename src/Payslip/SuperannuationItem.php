<?php

declare(strict_types=1);

namespace App\Payslip;

final class SuperannuationItem
{
    /** @var string */
    private $fund;

    /** @var float */
    private $percentage;

    public function __construct(string $fund, float $percentage)
    {
        $this->fund       = $fund;
        $this->percentage = $percentage;
    }

    public function getFund(): string
    {
        return $this->fund;
    }

    public function getPercentage(): float
    {
        return $this->percentage;
    }
}
