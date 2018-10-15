<?php

declare(strict_types=1);

namespace App\Tests\Services;

use App\Config\PayConfig;
use App\Config\Shift;
use App\Config\ShiftType;
use App\Config\TaxType;
use App\Services\PayslipGenerator;
use PHPUnit\Framework\TestCase;

final class PayslipGeneratorTest extends TestCase
{
    public function testEmptyPayslipGeneratedWhenNoShiftsGiven(): void
    {
        $generator = new PayslipGenerator();
        $payConfig = new PayConfig(10, 38, 1.5, [], []);

        $payslip = $generator->generate($payConfig);

        static::assertSame(0.0, $payslip->getGrossPay(), 'Gross pay should be 0');
        static::assertSame(0.0, $payslip->getDeductions(), 'Deductions should be 0');
        static::assertSame(0.0, $payslip->getNetPay(), 'Net pay should be 0');
        static::assertSame([], $payslip->getSections(), 'Sections should be empty');
    }

    public function testSingleShiftGeneratedCorrectly(): void
    {
        static::markTestIncomplete('The generator has not been implemented yet');

        $generator     = new PayslipGenerator();
        $shiftTypeName = 'Ordinary';
        $shiftTypes    = [
            new ShiftType($shiftTypeName, 1.0),
        ];
        $taxTypes = [
            new TaxType(0.1),
        ];

        $shift     = new Shift($shiftTypeName, 5);
        $payConfig = new PayConfig(10, 10, 2, $shiftTypes, $taxTypes);

        $payslip = $generator->generate($payConfig, $shift);

        static::assertSame(50, $payslip->getGrossPay(), 'Gross pay should be base * hours');
        static::assertSame(5, $payslip->getDeductions(), 'Deductions should be tax * gross pay');
        static::assertSame(45, $payslip->getNetPay(), 'Net pay should be gross pay - deductions');
    }

    public function testSingleOvertimeShiftWithGeneratedCorrectly(): void
    {
        static::markTestIncomplete('The generator has not been implemented yet');

        $generator     = new PayslipGenerator();
        $shiftTypeName = 'Ordinary';
        $shiftTypes    = [
            new ShiftType($shiftTypeName, 1.0),
        ];
        $taxTypes = [
            new TaxType(0.1),
        ];

        $shift     = new Shift($shiftTypeName, 5);
        $payConfig = new PayConfig(10, 0, 2, $shiftTypes, $taxTypes);

        $payslip = $generator->generate($payConfig, $shift);

        static::assertSame(100, $payslip->getGrossPay(), 'Gross pay should be base * hours');
        static::assertSame(10, $payslip->getDeductions(), 'Deductions should be tax * gross pay');
        static::assertSame(90, $payslip->getNetPay(), 'Net pay should be gross pay - deductions');
    }
}
