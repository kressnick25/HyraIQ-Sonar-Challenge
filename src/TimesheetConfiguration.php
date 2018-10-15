<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class TimesheetConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $root = $treeBuilder->root('employee');

        $root
            ->children()
            ->arrayNode('superannuation')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('fundName')->end()
                        ->floatNode('percentage')->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('timesheet')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('type')->end()
                        ->floatNode('hours')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
