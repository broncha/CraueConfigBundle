<?php

namespace Craue\ConfigBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor,
    Symfony\Component\Config\FileLocator,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Definition,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Symfony\Component\DependencyInjection\Reference,
    Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Registration of the extension via DI.
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @author broncha <broncha@rajesharma.com>
 * @copyright 2011-2013 Christian Raue
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class CraueConfigExtension extends Extension {

    /**
     * {@inheritDoc}
     */
    public function load(array $config, ContainerBuilder $container) {
        $processor = new Processor();
        $configuration = new Configuration();

        $configs = $processor->processConfiguration($configuration, $config);
        
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('twig.xml');
        $loader->load('util.xml');

        if ($configs['cache'] !== FALSE) {
            $this->initializeCache(strtolower($configs['cache']), $container);
        }
    }

    private function initializeCache($cache, ContainerBuilder $container) {
        if (!$this->isSupported($cache)) {
            $msg = sprintf("The cache %s is not supported", $cache);
            throw new \InvalidArgumentException($msg);
        }

        $cacheHandler = NULL;
        switch ($cache) {
            case "apc":
                $cacheHandler = "Doctrine\\Common\\Cache\\ApcCache";
                break;
            default:
                $cacheHandler = "Doctrine\\Common\\Cache\\ArrayCache";
        }

        $definition = new Definition($cacheHandler);
        $container->setDefinition("craue_cache_handler", $definition);

        $container->getDefinition('craue_config')
                ->addMethodCall('setCacheHandler', array(new Reference('craue_cache_handler')));
    }

    private function isSupported($cache) {
        $supportedCaches = array("apc", "array");

        if (in_array($cache, $supportedCaches))
            return TRUE;
        return FALSE;
    }

}
