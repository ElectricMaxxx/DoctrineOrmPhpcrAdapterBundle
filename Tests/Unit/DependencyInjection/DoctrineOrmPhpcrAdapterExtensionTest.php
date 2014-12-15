<?php

namespace Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Tests\Unit\DependencyInjection;


use Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\DependencyInjection\DoctrineOrmPhpcrAdapterExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class DoctrineOrmPhpcrAdapterExtensionTest extends AbstractExtensionTestCase
{
    /**
     * Return an array of container extensions you need to be registered for each test (usually just the container
     * extension you are testing.
     *
     * @return ExtensionInterface[]
     */
    protected function getContainerExtensions()
    {
        return array(
            new DoctrineOrmPhpcrAdapterExtension(),
        );
    }

    public function testDefaults()
    {
        $this->load(array());
    }

    public function testManagerAdapterSetting()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array()
        );
        $this->container->setParameter('kernel.root_dir', 'test');
        $this->container->setParameter('kernel.environment', 'test');
        $this->load(array(
            'managers' => array(
                'reference-phpcr'    => array('default' => 'doctrine_phpcr.odm.default_document_manager'),
                'reference-dbal-orm' => array('default' => 'doctrine.orm.default_entity_manager',
                ),
            ),
            'adapter'  => array(
                'auto_mapping' => true,
                'auto_generate_proxy_classes' => false,
                'mappings' => array(),
            ),
        ));

        $this->assertContainerBuilderHasParameter('doctrine_orm_phpcr_adapter.adapter.default_adapter_manager');
        $this->assertContainerBuilderHasService('doctrine_orm_phpcr_adapter.adapter.default_adapter_manager');
        $this->assertContainerBuilderHasService('doctrine_orm_phpcr_adapter.adapter.default_event_manager');
        $this->assertContainerBuilderHasService('doctrine_orm_phpcr_adapter.adapter.default_configuration');
    }

    public function testDefaultSessionSetting()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array()
        );
        $this->container->setParameter('kernel.root_dir', 'test');
        $this->container->setParameter('kernel.environment', 'test');
        $this->load(array(
            'managers' => array(
                'reference-phpcr'    => array('default' => 'doctrine_phpcr.odm.default_document_manager'),
                'reference-dbal-orm' => array('default' => 'doctrine.orm.default_entity_manager',
                ),
            ),
            'adapter'  => array(
                'auto_mapping' => true,
                'auto_generate_proxy_classes' => false,
                'mappings' => array(),
            ),
        ));

        $this->assertContainerBuilderHasParameter('doctrine_orm_phpcr_adapter.sessions', array(
            'default' => 'doctrine_orm_phpcr_adapter.default_session',
        ));

        $this->assertContainerBuilderHasService('doctrine_orm_phpcr_adapter.event.phpcr', 'Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Event\PhpcrListener');
        $this->assertContainerBuilderHasService('doctrine_orm_phpcr_adapter.event.orm', 'Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Event\OrmListener');
        $this->assertContainerBuilderHasServiceDefinitionWithTag('doctrine_orm_phpcr_adapter.event.phpcr', 'doctrine_phpcr.event_subscriber');
        $this->assertContainerBuilderHasServiceDefinitionWithTag('doctrine_orm_phpcr_adapter.event.orm', 'doctrine.event_subscriber');
    }
}
