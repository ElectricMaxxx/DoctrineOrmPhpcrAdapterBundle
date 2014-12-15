<?php

namespace Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Tests\Unit\DependencyInjection;

use  Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\DependencyInjection\Configuration;
use Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\DependencyInjection\DoctrineOrmPhpcrAdapterExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class ConfigurationTest extends AbstractExtensionConfigurationTestCase
{
    protected function getContainerExtension()
    {
        return new DoctrineOrmPhpcrAdapterExtension();
    }

    protected function getConfiguration()
    {
        return new Configuration();
    }

    public function testDefaultsForAllConfigFormats()
    {
        $expectedConfiguration = array(
            'managers'  => array(
                array('name' => 'default', 'service' => 'doctrine_phpcr.odm.default_document_manager', 'type' => 'reference-phpcr'),
                array('name' => 'default', 'service' => 'doctrine.orm.default_entity_manager', 'type' => 'reference-dbal-orm'),
                array('name' => 'default', 'service' => 'doctrine_phpcr.odm.default_document_manager', 'type' => 'reference-phpcr'), // remove that duplication, when i find that
                array('name' => 'default', 'service' => 'doctrine.orm.default_entity_manager', 'type' => 'reference-dbal-orm'),
            ),
            'adapter'   => array(
                'auto_generate_proxy_classes' => true,
                'default_adapter_manager'     => 'default',
                'adapter_managers' => array(
                    'default'   => array(
                        'mappings'                  => array(
                            'test_mapping' => array(
                                'type'      => 'annotation',
                                'prefix'    => '\Entity',
                                'dir'       => '%kernel.root_dir%/../Entity',
                                'is_bundle' => false,
                                'mapping'   => true,
                            ),
                        ),
                        'auto_mapping'          => true,
                        'metadata_cache_driver' => array(
                            'type' => 'array',
                        ),
                        'class_metadata_factory_name' => 'Doctrine\ORM\ODMAdapter\Mapping\ClassMetadataFactory',
                        ),
                    ),
                'proxy_dir' => '%kernel.cache_dir%/doctrine/PHPCRProxies',
                'proxy_namespace' => 'PHPCRProxies'
                ),
        );

        $sources = array_map(function ($path) {
            return __DIR__.'/../../Resources/Fixtures/'.$path;
        }, array(
            'config/config.yml',
            'config/config.php',
        ));

        $this->assertProcessedConfigurationEquals($expectedConfiguration, $sources);
    }
}

