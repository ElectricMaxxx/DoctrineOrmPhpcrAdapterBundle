<?php

namespace Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Event;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnClearEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;

class OrmListener extends AbstractListener
{
    public function prePersist(LifecycleEventArgs $event)
    {
        $objectAdapterManager = $this->container->get('doctrine_orm_phpcr_adapter.adapter.default_adapter_manager');
        $object = $event->getEntity();
        if ($this->isReferenceable($object, $objectAdapterManager)) {
            $objectAdapterManager->persistReference($object);
        }

    }

    public function preUpdate(LifecycleEventArgs $event)
    {
        $objectAdapterManager = $this->container->get('doctrine_orm_phpcr_adapter.adapter.default_adapter_manager');
        $object = $event->getObject();
        if ($this->isReferenceable($object, $objectAdapterManager)) {
            #$objectAdapterManager->persistReference($object);
        }
    }

    /**
     * When a proxy comes it should be load again.
     *
     * @param LifecycleEventArgs $event
     */
    public function postLoad(LifecycleEventArgs $event)
    {
        $objectAdapterManager = $this->container->get('doctrine_orm_phpcr_adapter.adapter.default_adapter_manager');
        $object = $event->getObject();
        if ($this->isReferenceable($object, $objectAdapterManager)) {
            $objectAdapterManager->findReference($object);
        }
    }

    public function preRemove(LifecycleEventArgs $event)
    {
        $objectAdapterManager = $this->container->get('doctrine_orm_phpcr_adapter.adapter.default_adapter_manager');
        $object = $event->getObject();
        if ($this->isReferenceable($object, $objectAdapterManager)) {
            $objectAdapterManager->removeReference($object);
        }
    }

    public function onClear(OnClearEventArgs $event)
    {
        $objectAdapterManager = $this->container->get('doctrine_orm_phpcr_adapter.adapter.default_adapter_manager');
        $objectAdapterManager->clear();
    }

    public function preFlush(PreFlushEventArgs $event)
    {
        $objectAdapterManager = $this->container->get('doctrine_orm_phpcr_adapter.adapter.default_adapter_manager');
        $objectAdapterManager->flushReference();
    }
}
