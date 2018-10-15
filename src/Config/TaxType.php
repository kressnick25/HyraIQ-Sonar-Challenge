<?php

declare(strict_types=1);

namespace App\Config;

final class TaxType
{
    /** @var string */
    private $name;

    /** @var float */
    private $rate;

    /** @var float */
    private $threshold;

    public function __construct(string $name, float $rate, float $threshold = 0)
    {
        $this->name      = $name;
        $this->rate      = $rate;
        $this->threshold = $threshold;
    }

    public function getName(): string
    {
        return $this->name;
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
