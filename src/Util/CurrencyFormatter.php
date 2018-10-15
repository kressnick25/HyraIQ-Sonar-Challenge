<?php

declare(strict_types=1);

namespace App\Util;

final class CurrencyFormatter
{
    public static function format(float $currency): string
    {
        return \sprintf('$ %s', \number_format($currency, 2));
    }
}
