<?php

namespace Pantheon\TwoFactorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class TwoFactorBundleExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $container->setParameter('two_factor_bundle', $this->processConfiguration(new Configuration(), $configs));
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }
}
