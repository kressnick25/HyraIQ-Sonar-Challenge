<?php

namespace App\Util;

final class CurrencyFormatter
{
    public static function format(float $currency): string
    {
        return \sprintf('$ %s', \number_format($currency, 2));
    }
}
