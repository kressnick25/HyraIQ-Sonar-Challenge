<?php

namespace App\Payslip;

interface SectionInterface
{
    public function getTitle(): string;
    public function getHeadings(): array;
    public function getRows(): array;
    public function getTotal(): float;
}
