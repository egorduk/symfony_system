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
            new \Twig_SimpleFilter('stageDateFormat', [AppRuntime::class, 'stageDateFormatFilter']),
            new \Twig_SimpleFilter('percent', [AppRuntime::class, 'percentFilter']),
            new \Twig_SimpleFilter('countSheet', [AppRuntime::class, 'countSheetFilter']),
            new \Twig_SimpleFilter('noData', [AppRuntime::class, 'noDataFilter']),
            new \Twig_SimpleFilter('avatar', [AppRuntime::class, 'avatarFilter']),
            new \Twig_SimpleFilter('ip', [AppRuntime::class, 'ipFilter']),
            new \Twig_SimpleFilter('additionalActivityInfo', [AppRuntime::class, 'additionalActivityInfoFilter']),
            new \Twig_SimpleFilter('defaultStringData', [AppRuntime::class, 'defaultStringDataFilter']),
            new \Twig_SimpleFilter('mobileNumber', [AppRuntime::class, 'mobileNumberFilter']),
            new \Twig_SimpleFilter('country', [AppRuntime::class, 'countryFilter']),
        ];
    }

    public function getName()
    {
        return 'app';
    }
}
