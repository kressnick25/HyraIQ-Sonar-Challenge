<?php

declare(strict_types=1);

namespace App\Tests\Services;

use App\Config\EmployeeTimesheet;
use App\Config\PayConfig;
use App\Config\Shift;
use App\Config\ShiftType;
use App\Config\SuperFund;
use App\Config\TaxType;
use App\Services\PayslipGenerator;
use PHPUnit\Framework\TestCase;

final class PayslipGeneratorTest extends TestCase
{
    public function testEmptyPayslipGeneratedWhenNoShiftsGiven(): void
    {
        $generator = new PayslipGenerator();
        $payConfig = new PayConfig(10, 38, 1.5, [], []);
        $timesheet = new EmployeeTimesheet([], []);

        $payslip = $generator->generate($payConfig, $timesheet);

        static::assertEquals(0.0, $payslip->getGrossPay(), 'Gross pay should be 0');
        static::assertEquals(0.0, $payslip->getDeductions(), 'Deductions should be 0');
        static::assertEquals(0.0, $payslip->getNetPay(), 'Net pay should be 0');
        static::assertSame([], $payslip->getSections(), 'Sections should be empty');
    }

    public function testSingleShiftGeneratedCorrectly(): void
    {
        //static::markTestIncomplete('The generator has not been implemented yet');

        $generator     = new PayslipGenerator();
        $shiftTypeName = 'Ordinary';
        $shiftTypes    = [
            new ShiftType($shiftTypeName, 1.0),
        ];
        $taxTypes = [
            new TaxType('PAYG', 0.1),
        ];

        $shifts     = [new Shift($shiftTypeName, 5)];
        $superFunds = [new SuperFund('Great fund', 0.1)];
        $timesheet = new EmployeeTimesheet($superFunds, $shifts);

        $payConfig = new PayConfig(10, 10, 2, $shiftTypes, $taxTypes);

        $payslip = $generator->generate($payConfig, $timesheet);

        static::assertEquals(50, $payslip->getGrossPay(), 'Gross pay should be base * hours');
        static::assertEquals(5, $payslip->getDeductions(), 'Deductions should be tax * gross pay');
        static::assertEquals(45, $payslip->getNetPay(), 'Net pay should be gross pay - deductions');
    }

    public function testSingleOvertimeShiftWithGeneratedCorrectly(): void
    {
        //static::markTestIncomplete('The generator has not been implemented yet');

        $generator     = new PayslipGenerator();
        $shiftTypeName = 'Ordinary';
        $shiftTypes    = [
            new ShiftType($shiftTypeName, 1.0),
        ];
        $taxTypes = [
            new TaxType('PAYG', 0.1),
        ];

        $shifts     = [new Shift($shiftTypeName, 5)];
        $superFunds = [new SuperFund('Great fund', 0.1)];
        $timesheet = new EmployeeTimesheet($superFunds, $shifts);

        $payConfig = new PayConfig(10, 0, 2, $shiftTypes, $taxTypes);

        $payslip = $generator->generate($payConfig, $timesheet);

        static::assertEquals(100, $payslip->getGrossPay(), 'Gross pay should be base * hours');
        static::assertEquals(10, $payslip->getDeductions(), 'Deductions should be tax * gross pay');
        static::assertEquals(90, $payslip->getNetPay(), 'Net pay should be gross pay - deductions');
    }
}
