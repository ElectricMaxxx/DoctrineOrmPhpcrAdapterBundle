<?php

namespace Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Proxy\Proxy;
use Doctrine\ORM\ODMAdapter\ObjectAdapterManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class AbstractListener implements EventSubscriber
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    public function __construct(Container $container)
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
        return array('prePersist', 'preUpdate', 'preFlush', 'postLoad', 'preRemove', 'onClear');
    }

    /**
     * Detects if an object is mapped and if it isn't scheduled in the UoW as an reference to avoid
     * circular references.
     *
     * @param $object
     * @param \Doctrine\ORM\ODMAdapter\ObjectAdapterManager $objectAdapterManager
     * @return bool
     */
    protected function isReferenceable($object, ObjectAdapterManager $objectAdapterManager)
    {
        return !$objectAdapterManager->hasValidMapping(get_class($object))
                || $objectAdapterManager->isReferenced($object)
                || $objectAdapterManager->isSleepingProxy($object)
                ? false : true;
    }

    /**
     * Detects if a chosen object is either an proxy or not.
     * Proxies need special behavior in some situations.
     *
     * @param $object
     * @return bool
     */
    protected function isProxy($object)
    {
        return $object instanceof Proxy;
    }
}
