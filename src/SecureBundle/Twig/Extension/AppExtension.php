<?php

namespace SecureBundle\Twig\Extension;

use SecureBundle\Twig\AppRuntime;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('price', [AppRuntime::class, 'priceFilter']),
            new \Twig_SimpleFilter('day', [AppRuntime::class, 'dayFilter']),
            new \Twig_SimpleFilter('defaultDateFormat', [AppRuntime::class, 'defaultDateFormatFilter']),
            new \Twig_SimpleFilter('percent', [AppRuntime::class, 'percentFilter']),
            new \Twig_SimpleFilter('countSheet', [AppRuntime::class, 'countSheetFilter']),
        ];
    }

    public function getName()
    {
        return 'app';
    }
}
