<?php

namespace SecureBundle\DependencyInjection\Compiler;

use SecureBundle\DependencyInjection\FileUploaderLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('assetic.filter_manager')->setPublic(true);
        $container->getDefinition('assetic.filter.cssrewrite')->setPublic(true);
        $container->getDefinition('assetic.controller')->setPublic(true);

        $definition = $container->getDefinition('oneup_uploader.routing.loader');
        $definition->setClass(FileUploaderLoader::class);

        $this->overrideOneupUploaderParameters($container);
    }

    private function overrideOneupUploaderParameters(ContainerBuilder $container)
    {
        $controllerParameters = $container->getParameter('oneup_uploader.controllers');
        $config = $container->getExtensionConfig('oneup_uploader')[0];

        foreach ($controllerParameters as $key => &$controllerParameter) {
            $controllerParameter[1]['name'] = $config['mappings'][$key]['custom_frontend']['name'] ?? null;
        }

        $container->setParameter('oneup_uploader.controllers', $controllerParameters);
    }
}