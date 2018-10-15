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

        $root = $treeBuilder->root('timesheets');

        $root
            ->arrayPrototype()
                ->children()
                    ->scalarNode('type')->end()
                    ->floatNode('hours')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
