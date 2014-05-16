<?php

use Symfony\Cmf\Component\Testing\HttpKernel\TestKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends TestKernel
{
    public function configure()
    {
        $this->requireBundleSets(array('phpcr_odm', 'doctrine_orm', 'default'));

        $this->addBundles(array(
            new \Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\DoctrineOrmPhpcrAdapterBundle(),
        ));
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.php');
    }
}
