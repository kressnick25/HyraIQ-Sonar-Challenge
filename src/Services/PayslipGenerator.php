<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\EmployeeTimesheet;
use App\Config\PayConfig;
use App\Config\Shift;
use App\Config\TaxType;
use App\Payslip\EarningsItem;
use App\Payslip\EarningsSection;
use App\Payslip\Payslip;
use App\Config\ShiftType;
use App\Payslip\SuperannuationItem;
use App\Config\SuperFund;
use App\Payslip\SuperannuationSection;
use App\Payslip\TaxItem;
use App\Payslip\TaxSection;


final class PayslipGenerator
{
    public function generate(PayConfig $config, EmployeeTimesheet $timesheet): Payslip
    {
        if ($config->getShiftTypes() == []) { // no shifts were supplied
            return new Payslip(0, 0);
        }

        // Calculate sections
        $earnings = $this->CalculateEarnings($config, $timesheet);
        $tax = $this->CalculateTax($config, $earnings->getTotal());
        $super = $this->CalculateSuper($timesheet, $earnings->getTotal() );

        // Construct Payslip with earnings sections
        $payslip = new Payslip($earnings->getTotal(), $tax->getTotal());
        $payslip->addSection($earnings);
        $payslip->addSection($super);
        $payslip->addSection($tax);

        return $payslip;
    }

    private function CalculateEarnings(PayConfig $config, EmployeeTimesheet $timesheet) {
        $totalHoursWorked = 0;
        $overtime = false;
        $earnings = new EarningsSection();
        foreach ($timesheet->getShifts() as $shift){
            // assert object type is Shift... see comment at end of file.
            if (is_object($shift) && $shift instanceof Shift){
                $shiftHours = $shift->getHours();
                $overTimeHours = 0;
                $shiftRate = $config->getBaseRate();
                $shiftTypeName = $shift->getType();

                //Get pay rate from type;
                $shiftRate *= $this->getRateMultiplyer($config, $shiftTypeName);

                // Check if overtime rate required
                if ($overtime){ // whole shift overtime
                    $shiftRate *= $config->getOvertimeMultiplier();
                    $overTimeHours = $shiftHours; // all shift hours worked were in overtime
                }
                else { // else add to overtime and check for half shift;
                    $totalHoursWorked += $shiftHours;
                    if ($totalHoursWorked > $config->getRegularHours()) {
                        // shift put employee into overtime
                        $overTimeHours = $totalHoursWorked - $config->getRegularHours();
                        $overtime = true;
                    }
                }

                // Add EarningsItems to earnings section
                if ($overTimeHours != 0) { // split to two EarningsItems for overtime pay
                    $regPayHours = $shiftHours - $overTimeHours;
                    $overTimeRate = $shiftRate * $config->getOvertimeMultiplier();

                    $earnings->addItem( new EarningsItem($shiftTypeName, $regPayHours, $shiftRate) );
                    $earnings->addItem( new EarningsItem($shiftTypeName." - OVERTIME", $overTimeHours, $overTimeRate));
                }
                else { // regular singular payslip
                    $earnings->addItem( new EarningsItem( $shiftTypeName, $shiftHours, $shiftRate ));
                }
            }
        }
        return $earnings;
    }

    private function getRateMultiplyer(PayConfig $config, string $shiftTypeName){
        foreach ($config->getShiftTypes() as $type){
            if (is_object($type) && $type instanceof ShiftType){
                if ($shiftTypeName == $type->getName()){
                    return $type->getMultiplier();
                }
            }
        }
        return null;
    }

    /*
     * Applies all Tax to a SuperannuationSection\
     * params: a PayConfig object, float of gross pay
     * returns: new TaxSection
     */
    private function CalculateTax(PayConfig $config, float $grossPay){
        $taxSection = new TaxSection($grossPay);
        foreach($config->getTaxTypes() as $taxType){
            if (is_object($taxType) && $taxType instanceof TaxType){
                if ($grossPay > $taxType->getThreshold()){
                    $taxSection->addItem( new TaxItem( $taxType->getName(), $taxType->getRate() ));
                }
            }
        }

        return $taxSection;
    }

    /*
     * Applies all super to a SuperannuationSection\
     * params: an EmployeeTimesheet object, float of gross pay
     * returns: new SuperannuationSection
     */
    private function CalculateSuper(EmployeeTimesheet $timesheet, float $grossPay ){
        $superSection = new SuperannuationSection($grossPay);
        foreach($timesheet->getSuperFunds() as $superFund){
            if(is_object($superFund) && $superFund instanceof SuperFund){
                $superSection->addItem(
                    new SuperannuationItem(
                        $superFund->getFundName(),
                        $superFund->getPercentage() ));
            }
        }

        return $superSection;
    }
    /*
     * NOTE: As seen above, I keep asserting types for Objects in Arrays.
     * In other OOP languages like Java or C#, would be able to cast these to objects
     * to allow to call methods, but apparently no need to cast in PHP. However, still
     * can't call methods without these assertions or else my IDE spits out errors :(
     */
}
