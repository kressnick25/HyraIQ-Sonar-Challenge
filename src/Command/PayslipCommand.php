<?php

namespace App\Command;

use App\ConfigLoader;
use App\TimesheetLoader;
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

    public function __construct(TimesheetLoader $timesheetLoader, ConfigLoader $configLoader)
    {
        parent::__construct();
        $this->timesheetLoader = $timesheetLoader;
        $this->configLoader    = $configLoader;
    }

    protected function configure()
    {
        $this->setName('app:payslip')
            ->setDescription('Generates an employees payslip')
            ->addArgument('settings', InputArgument::REQUIRED, 'YAML file containing pay configuration')
            ->addArgument('timesheet', InputArgument::REQUIRED, 'YAML file containing a list of shifts')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $config              = $this->configLoader->load($input->getArgument('settings'));
        $timesheetCollection = $this->timesheetLoader->load($input->getArgument('timesheet'));
    }
}
