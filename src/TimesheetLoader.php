<?php

declare(strict_types=1);

namespace App;

use App\Config\EmployeeTimesheet;
use App\Config\Shift;
use App\Config\SuperFund;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Yaml\Yaml;

final class TimesheetLoader
{
    /** @var DenormalizerInterface */
    private $denormalizer;

    public function __construct(DenormalizerInterface $denormalizer)
    {
        $this->denormalizer = $denormalizer;
    }

    public function load(string $timesheetPath): EmployeeTimesheet
    {
        $configArray = Yaml::parseFile($timesheetPath);
        $processor   = new Processor();

        $config = $processor->processConfiguration(new TimesheetConfiguration(), $configArray);

        $shifts = [];
        foreach ($config['timesheet'] as $shiftConfig) {
            $shifts[] = $this->denormalizer->denormalize($shiftConfig, Shift::class);
        }

        $superFunds = [];
        foreach ($config['superannuation'] as $superConfig) {
            $superFunds[] = $this->denormalizer->denormalize($superConfig, SuperFund::class);
        }

        return new EmployeeTimesheet($superFunds, $shifts);
    }
}
