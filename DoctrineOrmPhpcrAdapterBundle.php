<?php

namespace Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle;

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

        if (class_exists('Doctrine\ODM\PHPCR\Version')) {
            $container->addCompilerPass(
                new RegisterEventListenersAndSubscribersPass(
                    'doctrine_phpcr.sessions',
                    'doctrine_orm_phpcr_adapter.adapter.%s_event_manager',
                    'doctrine_bridge'
                ),
                PassConfig::TYPE_BEFORE_OPTIMIZATION
            );
        }
    }
}
