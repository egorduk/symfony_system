<?php

namespace SecureBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('assetic.filter_manager')->setPublic(true);
        $container->getDefinition('assetic.filter.cssrewrite')->setPublic(true);
        $container->getDefinition('assetic.controller')->setPublic(true);
    }
}
