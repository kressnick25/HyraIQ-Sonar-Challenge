<?php

namespace App\Writer;

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
    }

    private function writeSection(SectionInterface $section): void
    {
        $this->io->section($section->getTitle());
        $headers = $section->getHeadings();
        $rows    = $section->getRows();

        $totalRow = \array_fill(0, \count($headers) - 2, '');
        $totalRow[] = 'Total:';
        $totalRow[] = \sprintf('$ %s', number_format($section->getTotal(), 2));

        $rows[] = new TableSeparator();
        $rows[] = $totalRow;
        $this->io->table($headers, $rows);
    }
}
