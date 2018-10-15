<?php

namespace App\Config;

final class SuperFund
{
    /** @var string */
    private $fundName;

    /** @var float */
    private $percentage;

    public function __construct(string $fundName, float $percentage)
    {
        $this->fundName   = $fundName;
        $this->percentage = $percentage;
    }

    public function getFundName(): string
    {
        return $this->fundName;
    }

    public function getPercentage(): float
    {
        return $this->percentage;
    }
}
