<?php

namespace App\Tests\Util;

use App\Util\CurrencyFormatter;
use App\Util\PercentageFormatter;
use PHPUnit\Framework\TestCase;

final class PercentageFormatterTest extends TestCase
{
    /**
     * @dataProvider getTests
     */
    public function testFormat(float $percentage, string $expected): void
    {
        $actual = PercentageFormatter::format($percentage);
        static::assertSame($expected, $actual);
    }

    public function getTests(): array
    {
        return [
            'No decimals adds two zeros' => [0.20, '20.00%'],
            'One decimal adds one zero' => [0.105, '10.50%'],
            'Many decimals rounds down' => [0.0342334958, '3.42%'],
            'Many decimals rounds up' => [0.518893847, '51.89%'],
        ];
    }
}
