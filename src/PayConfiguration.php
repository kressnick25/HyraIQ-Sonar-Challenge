<?php

namespace App;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class PayConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $root = $treeBuilder->root('settings');

        $root
            ->children()
                ->floatNode('baseRate')->end()
                ->floatNode('regularHours')->end()
                ->floatNode('overtimeMultiplier')->end()
                ->arrayNode('shiftTypes')
                    ->useAttributeAsKey('name')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('taxTypes')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->floatNode('rate')->end()
                            ->floatNode('threshold')->defaultValue(0)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;


        return $treeBuilder;
    }
}
