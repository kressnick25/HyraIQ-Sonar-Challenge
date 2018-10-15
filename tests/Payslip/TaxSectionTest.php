<?php

declare(strict_types=1);

namespace App\Tests\Payslip;

use App\Payslip\TaxItem;
use App\Payslip\TaxSection;
use PHPUnit\Framework\TestCase;

class TaxSectionTest extends TestCase
{
    /** @var TaxSection */
    private $section;

    protected function setUp(): void
    {
        $item1         = new TaxItem('PAYG Tax', 0.1);
        $item2         = new TaxItem('hecs help', 0.05);
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
