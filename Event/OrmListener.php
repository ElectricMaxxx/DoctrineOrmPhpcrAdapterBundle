<?php

namespace Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\Event\ManagerEventArgs;
use Doctrine\ORM\Event\OnClearEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\ODMAdapter\ObjectAdapterManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OrmListener implements EventSubscriber
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
            'postUpdate',
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
        $this->oam->persistReference($eventArgs->getObject());
    }

    /**
     * Wil trigger the ObjectAdapterManager to persist the referenced Object and sync the
     * mapped common fields.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->oam = $this->container->get('doctrine_orm_phpcr_adapter.default_adapter_manager');
        print("\n HOOK-Update\n");
        $this->oam->persistReference($eventArgs->getObject());
    }

    /**
     * Will trigger the ObjectAdapterManager to load the referenced objects.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $this->oam = $this->container->get('doctrine_orm_phpcr_adapter.default_adapter_manager');
        $this->oam->findReference($eventArgs->getObject());
    }

    /**
     * Will trigger the ObjectAdapterManager to remove the referenced objects.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $this->oam = $this->container->get('doctrine_orm_phpcr_adapter.default_adapter_manager');
        $this->oam->removeReference($eventArgs->getObject());
    }

    public function preFlush(PreFlushEventArgs $eventArgs)
    {
        $this->oam = $this->container->get('doctrine_orm_phpcr_adapter.default_adapter_manager');
        $this->oam->flushReference();
    }

    public function onClear(OnClearEventArgs $eventArgs)
    {
        $this->oam = $this->container->get('doctrine_orm_phpcr_adapter.default_adapter_manager');
        $this->oam->clear();
    }
}
