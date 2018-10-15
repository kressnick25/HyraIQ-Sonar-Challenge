<?php

namespace App\Config;

final class TaxConfig
{
    /** @var float */
    private $rate;

    /** @var float */
    private $threshold;

    public function __construct(float $rate, float $threshold = 0)
    {
        $this->rate = $rate;
        $this->threshold = $threshold;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getThreshold(): float
    {
        return $this->threshold;
    }
}
