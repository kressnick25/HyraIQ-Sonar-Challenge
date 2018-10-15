<?php

declare(strict_types=1);

namespace App\Payslip;

final class TaxItem
{
    /** @var string */
    private $taxType;

    /** @var float */
    private $rate;

    public function __construct(string $taxType, float $rate)
    {
        $this->taxType = $taxType;
        $this->rate    = $rate;
    }

    public function getTaxType(): string
    {
        return $this->taxType;
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}
