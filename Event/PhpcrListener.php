<?php

namespace Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Event;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\Event\ManagerEventArgs;

class PhpcrListener extends AbstractListener
{
    public function prePersist(LifecycleEventArgs $event)
    {
        $objectAdapterManager = $this->container->get('doctrine_orm_phpcr_adapter.adapter.default_adapter_manager');
        $object = $event->getObject();
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

    public function onClear(ManagerEventArgs $event)
    {
        $objectAdapterManager = $this->container->get('doctrine_orm_phpcr_adapter.adapter.default_adapter_manager');
        $objectAdapterManager->clear();
    }

    public function preFlush(ManagerEventArgs $event)
    {
        $objectAdapterManager = $this->container->get('doctrine_orm_phpcr_adapter.adapter.default_adapter_manager');
        $objectAdapterManager->flushReference();
    }
}
