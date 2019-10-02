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
use App\Payslip\EarningsSection;
use App\Payslip\SuperannuationSection;


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
        static::assertEquals(0, $this->getSuperTotal($payslip), 'Super should be grosspay * super_rate');
        static::assertSame([], $payslip->getSections(), 'Sections should be empty');
    }

    public function testSingleShift(): void
    {
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
        static::assertEquals(5, $this->getSuperTotal($payslip), 'Super should be grosspay * super_rate');
    }

    public function testSingleOvertimeShift(): void
    {
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
        static::assertEquals(10, $this->getSuperTotal($payslip), 'Super should be grosspay * super_rate');
    }

    public function testMultipleShift(): void
    {
        $generator     = new PayslipGenerator();
        $shiftTypeName = 'Ordinary';
        $shiftTypes    = [
            new ShiftType($shiftTypeName, 1.0),
        ];
        $taxTypes = [
            new TaxType('PAYG', 0.1),
        ];

        $shifts     = [new Shift($shiftTypeName, 5), new Shift($shiftTypeName, 10)];
        $superFunds = [new SuperFund('Great fund', 0.1)];
        $timesheet = new EmployeeTimesheet($superFunds, $shifts);

        $payConfig = new PayConfig(10, 20, 2, $shiftTypes, $taxTypes);

        $payslip = $generator->generate($payConfig, $timesheet);

        static::assertEquals(150, $payslip->getGrossPay(), 'Gross pay should be base * hours');
        static::assertEquals(15, $payslip->getDeductions(), 'Deductions should be tax * gross pay');
        static::assertEquals(135, $payslip->getNetPay(), 'Net pay should be gross pay - deductions');
        static::assertEquals(15, $this->getSuperTotal($payslip), 'Super should be grosspay * super_rate');
    }

    public function testMultipleShiftWithOvertime(): void
    {
        $generator     = new PayslipGenerator();
        $shiftTypeName = 'Ordinary';
        $shiftTypes    = [
            new ShiftType($shiftTypeName, 1.0),
        ];
        $taxTypes = [
            new TaxType('PAYG', 0.1),
        ];

        $shifts     = [new Shift($shiftTypeName, 5), new Shift($shiftTypeName, 10)];
        $superFunds = [new SuperFund('Great fund', 0.1)];
        $timesheet = new EmployeeTimesheet($superFunds, $shifts);

        $payConfig = new PayConfig(10, 10, 2, $shiftTypes, $taxTypes);

        $payslip = $generator->generate($payConfig, $timesheet);

        static::assertEquals(200, $payslip->getGrossPay(), 'Gross pay should be base * hours');
        static::assertEquals(20, $payslip->getDeductions(), 'Deductions should be tax * gross pay');
        static::assertEquals(180, $payslip->getNetPay(), 'Net pay should be gross pay - deductions');
        static::assertEquals(20, $this->getSuperTotal($payslip), 'Super should be grosspay * super_rate');
    }

    public function testMultipleSuper(): void
    {
        $generator     = new PayslipGenerator();
        $shiftTypeName = 'Ordinary';
        $shiftTypes    = [
            new ShiftType($shiftTypeName, 1.0),
        ];
        $taxTypes = [
            new TaxType('PAYG', 0.1),
        ];

        $shifts     = [new Shift($shiftTypeName, 10)];
        $superFunds = [new SuperFund('Great fund', 0.1), new SuperFund('Kinda OK Fund', 0.05)];
        $timesheet = new EmployeeTimesheet($superFunds, $shifts);

        $payConfig = new PayConfig(10, 10, 2, $shiftTypes, $taxTypes);

        $payslip = $generator->generate($payConfig, $timesheet);

        static::assertEquals(15, $this->getSuperTotal($payslip), 'Super should be grosspay * super_rate');
    }

    public function testMultipleShiftTypes(): void
    {
        $generator     = new PayslipGenerator();
        $shiftTypes    = [
            new ShiftType('Regular', 1.0), new ShiftType('PublicHoliday', 2.0), 
        ];
        $taxTypes = [
            new TaxType('PAYG', 0.1),
        ];

        $shifts     = [new Shift('Regular', 5), new Shift('PublicHoliday', 10)];
        $superFunds = [new SuperFund('Great fund', 0.1)];
        $timesheet = new EmployeeTimesheet($superFunds, $shifts);

        $payConfig = new PayConfig(10, 20, 2, $shiftTypes, $taxTypes);

        $payslip = $generator->generate($payConfig, $timesheet);

        static::assertEquals(250, $payslip->getGrossPay(), 'Gross pay should be base * hours');
        static::assertEquals(25, $payslip->getDeductions(), 'Deductions should be tax * gross pay');
        static::assertEquals(225, $payslip->getNetPay(), 'Net pay should be gross pay - deductions');
        static::assertEquals(25, $this->getSuperTotal($payslip), 'Super should be grosspay * super_rate');
    }

    public function testMultipleShiftTypesWithOvertimeOnHighRate(): void
    {
        $generator     = new PayslipGenerator();
        $shiftTypes    = [
            new ShiftType('Regular', 1.0), new ShiftType('PublicHoliday', 2.0), 
        ];
        $taxTypes = [
            new TaxType('PAYG', 0.1),
        ];

        $shifts     = [new Shift('Regular', 5), new Shift('PublicHoliday', 10)];
        $superFunds = [new SuperFund('Great fund', 0.1)];
        $timesheet = new EmployeeTimesheet($superFunds, $shifts);

        $payConfig = new PayConfig(10, 5, 2, $shiftTypes, $taxTypes);

        $payslip = $generator->generate($payConfig, $timesheet);

        static::assertEquals(450, $payslip->getGrossPay(), 'Gross pay should be base * hours');
        static::assertEquals(45, $payslip->getDeductions(), 'Deductions should be tax * gross pay');
        static::assertEquals(405, $payslip->getNetPay(), 'Net pay should be gross pay - deductions');
        static::assertEquals(45, $this->getSuperTotal($payslip), 'Super should be grosspay * super_rate');
    }

    public function testMultipleTaxTypes(): void
    {
        $generator     = new PayslipGenerator();
        $shiftTypeName = 'Ordinary';
        $shiftTypes    = [
            new ShiftType($shiftTypeName, 1.0),
        ];
        $taxTypes = [
            new TaxType('PAYG', 0.1), new TaxType('Luxury Tax', 0.2),
        ];

        $shifts     = [new Shift($shiftTypeName, 5), new Shift($shiftTypeName, 10)];
        $superFunds = [new SuperFund('Great fund', 0.1)];
        $timesheet = new EmployeeTimesheet($superFunds, $shifts);

        $payConfig = new PayConfig(10, 20, 2, $shiftTypes, $taxTypes);

        $payslip = $generator->generate($payConfig, $timesheet);

        static::assertEquals(150, $payslip->getGrossPay(), 'Gross pay should be base * hours');
        static::assertEquals(45, $payslip->getDeductions(), 'Deductions should be tax * gross pay');
        static::assertEquals(105, $payslip->getNetPay(), 'Net pay should be gross pay - deductions');
        static::assertEquals(15, $this->getSuperTotal($payslip), 'Super should be grosspay * super_rate');
    }

    public function testNumberShiftsMatchNumberEarningsItems(): void
    {
        $generator     = new PayslipGenerator();
        $shiftTypeName = 'Ordinary';
        $shiftTypes    = [
            new ShiftType($shiftTypeName, 1.0),
        ];
        $taxTypes = [
            new TaxType('PAYG', 0.1),
        ];

        $shifts     = [new Shift($shiftTypeName, 5), new Shift($shiftTypeName, 10)];
        $superFunds = [new SuperFund('Great fund', 0.1)];
        $timesheet = new EmployeeTimesheet($superFunds, $shifts);

        $payConfig = new PayConfig(10, 20, 2, $shiftTypes, $taxTypes);

        $payslip = $generator->generate($payConfig, $timesheet);

        $earningsItems; 
        # get the earnings section
        foreach($payslip->getSections() as $section){
            if($section instanceof EarningsSection){
                $earningsItems = $section->getRows();
            }
        }
        static::assertEquals(2, sizeof($earningsItems));
    }

    public function testOvertimeShiftGeneratesMultipleEarningsItems(): void
    {
        $generator     = new PayslipGenerator();
        $shiftTypeName = 'Ordinary';
        $shiftTypes    = [
            new ShiftType($shiftTypeName, 1.0),
        ];
        $taxTypes = [
            new TaxType('PAYG', 0.1),
        ];

        $shifts     = [new Shift($shiftTypeName, 10)];
        $superFunds = [new SuperFund('Great fund', 0.1)];
        $timesheet = new EmployeeTimesheet($superFunds, $shifts);

        $payConfig = new PayConfig(10, 5, 2, $shiftTypes, $taxTypes);

        $payslip = $generator->generate($payConfig, $timesheet);

        $earningsItems; 
        # get the earnings section
        foreach($payslip->getSections() as $section){
            if($section instanceof EarningsSection){
                $earningsItems = $section->getRows();
            }
        }
        static::assertEquals(2, sizeof($earningsItems));
    }

    public function testTaxAppliedOverThreshold(): void
    {
        $generator     = new PayslipGenerator();
        $shiftTypeName = 'Ordinary';
        $shiftTypes    = [
            new ShiftType($shiftTypeName, 1.0),
        ];
        $taxTypes = [
            new TaxType('PAYG', 0.1, 100),
        ];

        $shifts     = [new Shift($shiftTypeName, 5), new Shift($shiftTypeName, 10)];
        $superFunds = [new SuperFund('Great fund', 0.1)];
        $timesheet = new EmployeeTimesheet($superFunds, $shifts);

        $payConfig = new PayConfig(10, 20, 2, $shiftTypes, $taxTypes);

        $payslip = $generator->generate($payConfig, $timesheet);

        static::assertEquals(150, $payslip->getGrossPay(), 'Gross pay should be base * hours');
        static::assertEquals(15, $payslip->getDeductions(), 'Deductions should be tax * gross pay');
        static::assertEquals(135, $payslip->getNetPay(), 'Net pay should be gross pay - deductions');
        static::assertEquals(15, $this->getSuperTotal($payslip), 'Super should be grosspay * super_rate');
    }

    public function testTaxNotAppliedUnderThreshold(): void
    {
        $generator     = new PayslipGenerator();
        $shiftTypeName = 'Ordinary';
        $shiftTypes    = [
            new ShiftType($shiftTypeName, 1.0),
        ];
        $taxTypes = [
            new TaxType('PAYG', 0.1, 200),
        ];

        $shifts     = [new Shift($shiftTypeName, 5), new Shift($shiftTypeName, 10)];
        $superFunds = [new SuperFund('Great fund', 0.1)];
        $timesheet = new EmployeeTimesheet($superFunds, $shifts);

        $payConfig = new PayConfig(10, 20, 2, $shiftTypes, $taxTypes);

        $payslip = $generator->generate($payConfig, $timesheet);

        static::assertEquals(150, $payslip->getGrossPay(), 'Gross pay should be base * hours');
        static::assertEquals(0, $payslip->getDeductions(), 'Deductions should be tax * gross pay');
        static::assertEquals(150, $payslip->getNetPay(), 'Net pay should be gross pay - deductions');
        static::assertEquals(15, $this->getSuperTotal($payslip), 'Super should be grosspay * super_rate');
    }

    public function testMultipleTaxThresholds(): void
    {
        $generator     = new PayslipGenerator();
        $shiftTypeName = 'Ordinary';
        $shiftTypes    = [
            new ShiftType($shiftTypeName, 1.0),
        ];
        $taxTypes = [
            new TaxType('PAYG', 0.1, 100), new TaxType('High Earner Tax', 0.4, 1500)
        ];

        $shifts     = [new Shift($shiftTypeName, 5), new Shift($shiftTypeName, 10)];
        $superFunds = [new SuperFund('Great fund', 0.1)];
        $timesheet = new EmployeeTimesheet($superFunds, $shifts);

        $payConfig = new PayConfig(10, 20, 2, $shiftTypes, $taxTypes);

        $payslip = $generator->generate($payConfig, $timesheet);

        static::assertEquals(150, $payslip->getGrossPay(), 'Gross pay should be base * hours');
        static::assertEquals(15, $payslip->getDeductions(), 'Deductions should be tax * gross pay');
        static::assertEquals(135, $payslip->getNetPay(), 'Net pay should be gross pay - deductions');
        static::assertEquals(15, $this->getSuperTotal($payslip), 'Super should be grosspay * super_rate');
    }

    private function getSuperTotal($payslip){
        $superTotal = 0;
        # get the super section
        foreach ($payslip->getSections() as $section){
            if ($section instanceof SuperannuationSection){
                $superTotal += $section->getTotal();
            }
        }

        return $superTotal;
    }
}
