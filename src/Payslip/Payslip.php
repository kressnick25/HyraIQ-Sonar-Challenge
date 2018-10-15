<?php

namespace App\Payslip;

final class Payslip
{
    /** @var SectionInterface[] */
    private $sections = [];

    /** @var float */
    private $grossPay;

    /** @var float */
    private $deductions;

    public function __construct(float $grossPay, float $deductions)
    {
        $this->grossPay   = $grossPay;
        $this->deductions = $deductions;
    }

    public function addSection(SectionInterface $section): self
    {
        $this->sections[] = $section;

        return $this;
    }

    /**
     * @return SectionInterface[]
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    public function getGrossPay(): float
    {
        return $this->grossPay;
    }

    public function getDeductions(): float
    {
        return $this->deductions;
    }

    public function getNetPay(): float
    {
        return $this->grossPay - $this->deductions;
    }
}
