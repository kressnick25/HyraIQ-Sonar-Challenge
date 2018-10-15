<?php

declare(strict_types=1);

namespace App\Command;

use App\ConfigLoader;
use App\Services\PayslipGenerator;
use App\TimesheetLoader;
use App\Writer\PayslipWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class PayslipCommand extends Command
{
    /** @var TimesheetLoader */
    private $timesheetLoader;

    /** @var ConfigLoader */
    private $configLoader;

    /** @var PayslipGenerator */
    private $generator;

    public function __construct(TimesheetLoader $timesheetLoader, ConfigLoader $configLoader, PayslipGenerator $generator)
    {
        parent::__construct();
        $this->timesheetLoader = $timesheetLoader;
        $this->configLoader    = $configLoader;
        $this->generator       = $generator;
    }

    protected function configure(): void
    {
        $this->setName('app:payslip')
            ->setDescription('Generates an employees payslip')
            ->addArgument('settings', InputArgument::REQUIRED, 'YAML file containing pay configuration')
            ->addArgument('timesheet', InputArgument::REQUIRED, 'YAML file containing a list of shifts')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        $config              = $this->configLoader->load($input->getArgument('settings'));
        $employeeTimesheet = $this->timesheetLoader->load($input->getArgument('timesheet'));

        $payslip = $this->generator->generate($config, $employeeTimesheet);

        $writer = new PayslipWriter($io);
        $writer->write($payslip);
    }
}
