<?php

declare(strict_types=1);

namespace App;

use App\Config\PayConfig;
use App\Config\ShiftType;
use App\Config\TaxType;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Yaml\Yaml;

final class ConfigLoader
{
    /** @var DenormalizerInterface */
    private $denormalizer;

    public function __construct(DenormalizerInterface $denormalizer)
    {
        $this->denormalizer = $denormalizer;
    }

    public function load(string $configPath): PayConfig
    {
        $configArray = Yaml::parseFile($configPath);
        $processor   = new Processor();

        $config = $processor->processConfiguration(new PayConfiguration(), $configArray);

        $shiftTypes = $this->loadShiftTypes($config);
        $taxTypes   = $this->loadTaxTypes($config);

        $payConfig = new PayConfig(
            $config['baseRate'],
            $config['regularHours'],
            $config['overtimeMultiplier'],
            $shiftTypes,
            $taxTypes
        );

        return $payConfig;
    }

    /**
     * @return ShiftType[]
     */
    private function loadShiftTypes(array $config): array
    {
        $shiftTypes = [];
        foreach ($config['shiftTypes'] as $name => $multiplier) {
            $shiftTypes[] = new ShiftType($name, $multiplier);
        }

        return $shiftTypes;
    }

    /**
     * @return TaxType[]
     */
    private function loadTaxTypes(array $config): array
    {
        $taxTypes = [];
        foreach ($config['taxTypes'] as $name => $taxTypeConfig) {
            $taxTypes[] = $this->denormalizer->denormalize($taxTypeConfig, TaxType::class, null, [
                'default_constructor_arguments' => [
                    TaxType::class => ['name' => $name],
                ],
            ]);
        }

        return $taxTypes;
    }
}
