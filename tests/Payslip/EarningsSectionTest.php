<?php

namespace App\Tests\Payslip;

use App\Payslip\EarningsItem;
use App\Payslip\EarningsSection;
use PHPUnit\Framework\TestCase;

class EarningsSectionTest extends TestCase
{
    /** @var EarningsSection */
    private $section;

    public function setUp()
    {
        $item1 = new EarningsItem('FakeShift', 1, 2);
        $item2 = new EarningsItem('anotherFakeShift', 3, 2);
        $this->section = (new EarningsSection())
            ->addItem($item1)
            ->addItem($item2)
        ;
    }

    public function testGetTotalReturnsSumOfItems(): void
    {
        static::assertSame(8.0, $this->section->getTotal());
    }

    public function testGetRowsReturnsRowForEachItem(): void
    {
        $expected = [
            ['FakeShift', 1.0, '$ 2.00', '$ 2.00'],
            ['AnotherFakeShift', 3.0, '$ 2.00', '$ 6.00'],
        ];

        static::assertSame($expected, $this->section->getRows());
    }
}
