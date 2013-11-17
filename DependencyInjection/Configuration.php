<?php
namespace Craue\ConfigBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 *
 * @author broncha <broncha@rajesharma.com>
 * @copyright 2013-2015 Rajesh Sharma
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Configuration implements ConfigurationInterface{
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('craue_config');
        
        $rootNode
            ->children()
                ->scalarNode('cache')
                    ->treatNullLike(FALSE)
                ->end()
            ->end();
        
        return $treeBuilder;
    }
}
