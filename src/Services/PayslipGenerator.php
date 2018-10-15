<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\EmployeeTimesheet;
use App\Config\PayConfig;
use App\Config\Shift;
use App\Payslip\Payslip;

final class PayslipGenerator
{
    public function generate(PayConfig $config, EmployeeTimesheet $timesheet): Payslip
    {
        return new Payslip(0, 0);
    }
}
