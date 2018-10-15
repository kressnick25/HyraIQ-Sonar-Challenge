<?php

namespace App\Tests\Payslip;

use App\Payslip\EarningsItem;
use App\Payslip\EarningsSection;
use App\Payslip\SuperannuationItem;
use App\Payslip\SuperannuationSection;
use PHPUnit\Framework\TestCase;

class SuperannuationSectionTest extends TestCase
{
    /** @var SuperannuationSection */
    private $section;

    public function setUp()
    {
        $item1 = new SuperannuationItem('Great Fund', 0.01);
        $item2 = new SuperannuationItem('lousy fund', 0.1);
        $this->section = (new SuperannuationSection(100))
            ->addItem($item1)
            ->addItem($item2)
        ;
    }

    public function testGetTotalReturnsSumOfItems(): void
    {
        static::assertSame(11.0, $this->section->getTotal());
    }

    public function testGetRowsReturnsRowForEachItem(): void
    {
        $expected = [
            ['Great Fund', '1.00%', '$ 1.00'],
            ['Lousy fund', '10.00%', '$ 10.00'],
        ];

        static::assertSame($expected, $this->section->getRows());
    }
}
