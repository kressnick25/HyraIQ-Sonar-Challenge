<?php

namespace App;

use App\Config\PayConfig;
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

        /** @var PayConfig $payConfig */
        $payConfig = $this->denormalizer->denormalize($config, PayConfig::class);

        return $payConfig;
    }
}
