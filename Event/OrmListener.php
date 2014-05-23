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
        $oam = $this->container->get('doctrine_orm_phpcr_adapter.default_adapter_manager');
        $object = $eventArgs->getObject();
        if ($this->isManagedByBridge($object, $oam)) {
            $oam->persistReference($eventArgs->getObject());
            print("PERSIST");
        }
    }

    /**
     * Wil trigger the ObjectAdapterManager to persist the referenced Object and sync the
     * mapped common fields.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $oam = $this->container->get('doctrine_orm_phpcr_adapter.default_adapter_manager');
        $object = $eventArgs->getObject();
        if ($this->isManagedByBridge($object, $oam)) {
            $oam->persistReference($eventArgs->getObject());
        }
    }

    /**
     * Will trigger the ObjectAdapterManager to load the referenced objects.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $oam = $this->container->get('doctrine_orm_phpcr_adapter.default_adapter_manager');
        $object = $eventArgs->getObject();
        if ($this->isManagedByBridge($object, $oam)) {
            $oam->findReference($eventArgs->getObject());
        }
    }

    /**
     * Will trigger the ObjectAdapterManager to remove the referenced objects.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $oam = $this->container->get('doctrine_orm_phpcr_adapter.default_adapter_manager');
        $object = $eventArgs->getObject();
        if ($this->isManagedByBridge($object, $oam)) {
            $oam->removeReference($eventArgs->getObject());
        }
    }

    /**
     * Triggers the flush on ObjectAdapterManager.
     *
     * @param PreFlushEventArgs $eventArgs
     */
    public function preFlush(PreFlushEventArgs $eventArgs)
    {
        $oam = $this->container->get('doctrine_orm_phpcr_adapter.default_adapter_manager');
        $oam->flushReference();
    }

    /**
     * Triggers the clear on the ObjectAdapterManager.
     *
     * @param OnClearEventArgs $eventArgs
     */
    public function onClear(OnClearEventArgs $eventArgs)
    {
        $oam = $this->container->get('doctrine_orm_phpcr_adapter.default_adapter_manager');
        $oam->flushReference();
    }

    /**
     * Detects if an object is mapped or not.
     *
     * @param $object
     * @param ObjectAdapterManager $objectAdapterManager
     * @return bool
     */
    private function isManagedByBridge($object, ObjectAdapterManager $objectAdapterManager)
    {
        $classMetadata = $objectAdapterManager->getClassMetadata(get_class($object));
        return null === $classMetadata ? false : true;
    }
}
