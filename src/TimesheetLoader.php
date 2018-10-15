<?php

namespace App;

use App\Config\Shift;
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

    /**
     * @return Shift[]
     */
    public function load(string $timesheetPath): array
    {
        $configArray = Yaml::parseFile($timesheetPath);
        $processor   = new Processor();

        $config = $processor->processConfiguration(new TimesheetConfiguration(), $configArray);

        $timesheetCollection = [];
        foreach ($config as $key => $value) {
            $timesheetCollection[] = $this->denormalizer->denormalize($value, Shift::class);
        }

        return $timesheetCollection;
    }
}
