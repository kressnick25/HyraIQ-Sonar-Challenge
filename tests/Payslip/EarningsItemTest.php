<?php

namespace App\Tests\Payslip;

use App\Payslip\EarningsItem;
use PHPUnit\Framework\TestCase;

final class EarningsItemTest extends TestCase
{
    /**
     * @dataProvider getTests
     */
    public function testGetTotal(float $hours, float $rate, float $expected): void
    {
        $item = new EarningsItem('FakeShift', $hours, $rate);
        static::assertSame($expected, $item->getTotal());
    }

    public function getTests(): array
    {
        return [
            'Zero hours returns zero' => [0, 1500, 0],
            'Zero rate returns zero' => [2750, 0, 0],
            'Round  numbers returns product' => [10, 2, 20],
            'Floating point numbers returns floating point' => [7, 12.3356, 86.3492],
        ];
    }
}
