<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            //new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            //new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new AuthBundle\AuthBundle(),
            new SecureBundle\SecureBundle(),
            new AdminBundle\AdminBundle(),
            new UserBundle\UserBundle(),
            //new Bmatzner\FontAwesomeBundle\BmatznerFontAwesomeBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            //new Symfony\Bundle\DebugBundle\DebugBundle(),
            //new Sensio\Bundle\DistributionBundle\SensioDistributionBundle(),
            //new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle(),
            new Oneup\UploaderBundle\OneupUploaderBundle(),
        ];

        //var_dump($this->getEnvironment());die;

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            //$bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            //$bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            //$bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }

    public function __construct($environment, $debug)
    {
        date_default_timezone_set('Europe/Minsk');

        parent::__construct($environment, $debug);
    }
}
