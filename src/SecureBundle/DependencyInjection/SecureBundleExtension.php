<?php

namespace SecureBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class SecureBundleExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('parameters.yml');
        $loader->load('repositories.yml');
        $loader->load('listeners.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('default_user_avatar', $config['user_avatars']['default']);
        $container->setParameter('default_man_user_avatar', $config['user_avatars']['default_man']);
        $container->setParameter('default_woman_user_avatar', $config['user_avatars']['default_woman']);
    }
}
