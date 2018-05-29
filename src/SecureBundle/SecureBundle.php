<?php

namespace SecureBundle;

use SecureBundle\DependencyInjection\Compiler\OverrideServiceCompilerPass;
use SecureBundle\DependencyInjection\SecureBundleExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SecureBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new SecureBundleExtension();
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new OverrideServiceCompilerPass());
    }
}
