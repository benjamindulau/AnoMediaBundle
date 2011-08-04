<?php

/**
 * This file is part of the AnoMediaBundle
 *
 * (c) anonymation <contact@anonymation.com>
 *
 */

namespace Ano\Bundle\MediaBundle\DependencyInjection;

use Ano\Bundle\SystemBundle\DependencyInjection\Extension;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

use Ano\Bundle\MediaBundle\Model\MediaContext;

/**
 * Initializes extension
 *
 * @author Benjamin Dulau <benjamin.dulau@anonymation.com>
 */
class AnoMediaExtension extends Extension
{
    /**
     * Loads configuration
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        foreach (array('provider', 'cdn', 'filesystem', 'generator', 'image', 'manager') as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $this->initCdns($config, $container);
        $this->initFilesystem($config, $container);
        $this->initGenerators($config, $container);
        $this->initProviders($config, $container);
        $this->initContexts($config, $container);

    }

    private function initCdns(array $config, ContainerBuilder $container)
    {
        // TODO
    }

    private function initFilesystem(array $config, ContainerBuilder $container)
    {
        // TODO
    }

    private function initGenerators(array $config, ContainerBuilder $container)
    {
        // TODO
    }

    private function initProviders(array $config, ContainerBuilder $container)
    {
        // TODO
    }

    private function initContexts(array $config, ContainerBuilder $container)
    {
//        foreach($contexts as $name => $options)
//        {
//            $context = new MediaContext($name);
//            $cdn = $container->getDefinition($context['cdn']['name']);
//            $cdn->replaceArgument(0, $context['cdn']['base_url']);
//
//            $provider = $container->getDefinition($options['provider']);
//            $provider->replaceArgument(0, $options['provider']);
//
//            //$context->setProvider()
//        }
    }

    public function getAlias()
    {
        return 'ano_media';
    }
}