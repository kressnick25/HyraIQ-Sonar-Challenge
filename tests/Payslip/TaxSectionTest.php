<?php

namespace App\Tests\Payslip;

use App\Payslip\EarningsItem;
use App\Payslip\EarningsSection;
use App\Payslip\SuperannuationItem;
use App\Payslip\SuperannuationSection;
use App\Payslip\TaxItem;
use App\Payslip\TaxSection;
use PHPUnit\Framework\TestCase;

class TaxSectionTest extends TestCase
{
    /** @var TaxSection */
    private $section;

    public function setUp()
    {
        $item1 = new TaxItem('PAYG Tax', 0.1);
        $item2 = new TaxItem('hecs help', 0.05);
        $this->section = (new TaxSection(100))
            ->addItem($item1)
            ->addItem($item2)
        ;
    }

    public function testGetTotalReturnsSumOfItems(): void
    {
        static::assertSame(15.0, $this->section->getTotal());
    }

    public function testGetRowsReturnsRowForEachItem(): void
    {
        $expected = [
            ['PAYG Tax', '10.00%', '$ 10.00'],
            ['Hecs help', '5.00%', '$ 5.00'],
        ];

        static::assertSame($expected, $this->section->getRows());
    }
}
