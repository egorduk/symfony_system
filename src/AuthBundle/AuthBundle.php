<?php

namespace AuthBundle;

use AuthBundle\DependencyInjection\AuthBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AuthBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new AuthBundleExtension();
    }
}
