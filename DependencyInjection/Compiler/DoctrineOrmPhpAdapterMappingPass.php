<?php

namespace Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\DependencyInjection\Compiler;

use Symfony\Bridge\Doctrine\DependencyInjection\CompilerPass\RegisterMappingsPass;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class for Symfony bundles to configure mappings for model classes not in the
 * automapped folder.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@onit-gmbh.de>
 */
class DoctrineOrmPhpAdapterMappingPass extends RegisterMappingsPass
{

    /**
     * You should not directly instantiate this class but use one of the
     * factory methods.
     *
     * @param Definition|Reference $driver
     * @param array|\string[] $namespaces
     * @param array $managerParameters
     * @param bool $enabledParameter
     */
    public function __construct($driver, $namespaces, array $managerParameters, $enabledParameter = false)
    {
        $managerParameters[] = 'doctrine_orm_phpcr_adapter.default_adapter_manager';
        parent::__construct(
            $driver,
            $namespaces,
            $managerParameters,
            'doctrine_orm_phpcr_adapter.%s_metadata_driver',
            $enabledParameter
        );
    }

    /**
     * @param array $mappings Hashmap of directory path to namespace
     * @param string[] $managerParameters List of parameters that could which object manager name
     *                                    your bundle uses. This compiler pass will automatically
     *                                    append the parameter name for the default entity manager
     *                                    to this list.
     * @param bool|string $enabledParameter Service container parameter that must be present to
     *                                    enable the mapping. Set to false to not do any check,
     *                                    optional.
     * @return DoctrineOrmPhpAdapterMappingPass
     */
    public static function createXmlMappingDriver(array $mappings, array $managerParameters = array(), $enabledParameter = false)
    {
        $arguments = array($mappings, '.bridge.xml');
        $locator = new Definition('Doctrine\Common\Persistence\Mapping\Driver\SymfonyFileLocator', $arguments);
        $driver = new Definition('Doctrine\ORM\ODMAdapter\Mapping\Driver\XmlDriver', array($locator));

        return new DoctrineOrmPhpAdapterMappingPass($driver, $mappings, $managerParameters, $enabledParameter);
    }

    /**
     * @param array $namespaces List of namespaces that are handled with annotation mapping
     * @param array $directories List of directories to look for annotated classes
     * @param string[] $managerParameters List of parameters that could which object manager name
     *                                    your bundle uses. This compiler pass will automatically
     *                                    append the parameter name for the default entity manager
     *                                    to this list.
     * @param bool|string $enabledParameter Service container parameter that must be present to
     *                                    enable the mapping. Set to false to not do any check,
     *                                    optional.
     * @return DoctrineOrmPhpAdapterMappingPass
     */
    public static function createAnnotationMappingDriver(array $namespaces, array $directories, array $managerParameters = array(), $enabledParameter = false)
    {
        $reader = new Reference('octrine_orm_phpcr_adapter.adapter.metadata.annotation_reader');
        $driver = new Definition('Doctrine\ORM\ODMAdapter\Mapping\Driver\AnnotationDriver', array($reader, $directories));

        return new DoctrineOrmPhpAdapterMappingPass($driver, $namespaces, $managerParameters, $enabledParameter);
    }
}
