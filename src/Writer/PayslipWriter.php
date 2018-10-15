<?php

namespace App\Writer;

use App\Util\CurrencyFormatter;
use App\Payslip\Payslip;
use App\Payslip\SectionInterface;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Style\SymfonyStyle;

class PayslipWriter
{
    /** @var SymfonyStyle */
    private $io;

    public function __construct(SymfonyStyle $io)
    {
        $this->io = $io;
    }

    public function write(Payslip $payslip): void
    {
        $this->io->title('Generated Payslip');

        foreach ($payslip->getSections() as $section) {
            $this->writeSection($section);
        }

        $this->writeTotals($payslip);
    }

    private function writeSection(SectionInterface $section): void
    {
        $this->io->section($section->getTitle());
        $headers = $section->getHeadings();
        $rows    = $section->getRows();

        $totalRow   = \array_fill(0, \count($headers) - 2, '');
        $totalRow[] = 'Total:';
        $totalRow[] = CurrencyFormatter::format($section->getTotal());

        $rows[] = new TableSeparator();
        $rows[] = $totalRow;
        $this->io->table($headers, $rows);
    }

    private function writeTotals(Payslip $payslip): void
    {
        $this->io->section('TOTALS');
        $this->io->table(
            [],
            [
                [
                    'Gross Pay',
                    CurrencyFormatter::format($payslip->getGrossPay()),
                ],
                [
                    'Gross Deductions',
                    CurrencyFormatter::format($payslip->getDeductions()),
                ],
                [
                    'Net Pay',
                    CurrencyFormatter::format($payslip->getGrossPay() - $payslip->getDeductions())
                ],
            ]
        );
    }
}
