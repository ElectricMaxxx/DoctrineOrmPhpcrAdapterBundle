<?php

namespace Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle;

use Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\DependencyInjection\Compiler\EventAdderCompilerPass;
use Symfony\Bridge\Doctrine\DependencyInjection\CompilerPass\RegisterEventListenersAndSubscribersPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Bundle Class for DoctrineOrmPhpcrAdapterBundle.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@onit-gmbh.de>
 */
class DoctrineOrmPhpcrAdapterBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}
