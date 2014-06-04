<?php

namespace Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\ODMAdapter\ObjectAdapterManager;
use Symfony\Component\DependencyInjection\Container;

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
                ? false : true;
    }
}
