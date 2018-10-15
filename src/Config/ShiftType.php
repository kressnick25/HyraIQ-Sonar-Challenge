<?php

namespace App\Config;

final class ShiftType
{
    /** @var string */
    private $name;

    private $multiplier;

    public function __construct(string $name, $multiplier)
    {
        $this->name = $name;
        $this->multiplier = $multiplier;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMultiplier()
    {
        return $this->multiplier;
    }
}
