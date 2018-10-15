<?php

namespace App\Payslip;

interface ItemInterface
{
    public function getTotal(): float;
}
