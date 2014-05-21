<?php

namespace Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\Event\ManagerEventArgs;
use Doctrine\Common\Persistence\Event\OnClearEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\ODMAdapter\ObjectAdapterManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PhpcrListener implements EventSubscriber
{
    /**
     * @var ObjectAdapterManager
     */
    private $oam;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
            'postLoad',
            'preRemove',
            'preFlush',
            'onClear',
        );
    }

    /**
     * Wil trigger the ObjectAdapterManager to persist the referenced Object and sync the
     * mapped common fields.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        // i wanted to avoid that but injecting the adapter directly causes Circular references
        $this->oam = $this->container->get('doctrine_orm_phpcr_adapter.default_adapter_manager');
        print("Hook-Persist-PHPCR \n");
    }

    /**
     * Wil trigger the ObjectAdapterManager to persist the referenced Object and sync the
     * mapped common fields.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->oam = $this->container->get('doctrine_orm_phpcr_adapter.default_adapter_manager');
        print("Hook-Update-PHPCR \n");
    }

    /**
     * Will trigger the ObjectAdapterManager to load the referenced object too.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $this->oam = $this->container->get('doctrine_orm_phpcr_adapter.default_adapter_manager');
        print("Hook-Load-PHPCR \n");
    }

    /**
     * Will trigger the ObjectAdapterManager to remove the referenced object too.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $this->oam = $this->container->get('doctrine_orm_phpcr_adapter.default_adapter_manager');
        print("Hook-Remove-PHPCR \n");
    }

    public function preFlush(ManagerEventArgs $eventArgs)
    {
        $this->oam = $this->container->get('doctrine_orm_phpcr_adapter.default_adapter_manager');
        print("Hook-Flush-PHPCR \n");
    }

    public function onClear(OnClearEventArgs $eventArgs)
    {
        $this->oam = $this->container->get('doctrine_orm_phpcr_adapter.default_adapter_manager');
        print("Hook-Clear-PHPCR \n");
    }
}
