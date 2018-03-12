<?php

namespace SecureBundle;

use SecureBundle\DependencyInjection\SecureBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SecureBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new SecureBundleExtension();
    }
}
