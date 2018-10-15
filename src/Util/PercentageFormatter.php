<?php

declare(strict_types=1);

namespace App\Util;

class PercentageFormatter
{
    public static function format(float $percentage): string
    {
        return \sprintf('%s%%', \number_format($percentage * 100, 2));
    }
}
