<?php

declare(strict_types=1);

namespace App\Tests\Util;

use App\Util\CurrencyFormatter;
use PHPUnit\Framework\TestCase;

final class CurrencyFormatterTest extends TestCase
{
    /**
     * @dataProvider getTests
     */
    public function testFormat(float $currency, string $expected): void
    {
        $actual = CurrencyFormatter::format($currency);
        static::assertSame($expected, $actual);
    }

    public function getTests(): array
    {
        return [
            'No decimals adds two zeros' => [20, '$ 20.00'],
            'One decimal adds one zero'  => [22.5, '$ 22.50'],
            'Many decimals rounds down'  => [25.33487871298374, '$ 25.33'],
            'Many decimals rounds up'    => [28.458872394, '$ 28.46'],
        ];
    }
}
