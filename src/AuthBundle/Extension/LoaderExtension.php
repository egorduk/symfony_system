<?php

namespace AuthBundle\Extension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class LoaderExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources1/config'));
        //$loader->load('parameters.yml');
        //$loader->load('fixtures.yml');
       // $loader->load('services.yml');
       // $loader->load('repositories.yml');
       // $loader->load('listeners.yml');
    }
}
