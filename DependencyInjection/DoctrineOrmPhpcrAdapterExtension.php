<?php

namespace Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\DependencyInjection;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\DependencyInjection\AbstractDoctrineExtension;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Process\Exception\LogicException;
use Doctrine\ORM\ODMAdapter\Reference as AdapterReference;

/**
 * This bundle's extension class.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@onit-gmbh.de>
 */
class DoctrineOrmPhpcrAdapterExtension extends AbstractDoctrineExtension
{
    private $bundleDirs;

    /**
     * List of current reference managers with the reference type as key.
     *
     * @var array|ObjectManager
     */
    private $referenceManagers = array();

    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @internal param array $config An array of configuration values
     * @api
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if (!empty($config['managers'])) {
            $this->loadReferencedManagers($config['managers'], $container);
        }

        if (!empty($config['adapter'])) {
            $this->loadAdapterMappings($config['adapter'], $container);
        }
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     * @throws \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function loadReferencedManagers(array $config, ContainerBuilder $container)
    {
        // the configuration setting will prevent us from using wrong type
        foreach ($config as $manager) {
                $this->referenceManagers[$manager['type']][$manager['name']] = new Reference($manager['service']);
        }
    }

    public function getAlias()
    {
        return 'doctrine_orm_phpcr_adapter';
    }

    private function loadAdapterMappings(array $config, ContainerBuilder $container)
    {
        $adapterManagers = array();
        foreach ($config['adapter_managers'] as $name => $adapterManager) {
            if (empty($config['default_adapter_manager'])) {
                $config['default_adapter_manager'] = $name;
            }

            $adapterManager['name'] = $name;
            $adapterManager['service_name'] = $adapterManagers[$name]
                                            = sprintf('doctrine_orm_phpcr_adapter.%s_adapter_manager', $name);
            if ($adapterManager['auto_mapping'] && count($config['adapter_managers']) > 1) {
                throw new LogicException(
                    'You cannot enable "auto_mapping" when several adapter managers are defined.'
                );
            }

            $this->loadAdapterManager($adapterManager, $container);
        }

        $container->setParameter('doctrine_orm_phpcr_adapter.adapter_managers', $adapterManagers);

        // no adapter manager configured
        if (empty($config['default_adapter_manager'])) {
            return;
        }

        $container->setParameter(
            'doctrine_orm_phpcr_adapter.adapter.default_adapter_manager',
            $config['default_adapter_manager']
        );
        $container->setAlias(
            'doctrine_orm_phpcr_adapter.adapter.adapter_manager',
            $adapterManagers[$config['default_adapter_manager']]
        );


        $options = array('auto_generate_proxy_classes', 'proxy_dir', 'proxy_namespace');
        foreach ($options as $key) {
            $container->setParameter('doctrine_orm_phpcr_adapter.adapter.' . $key, $config[$key]);
        }

    }

    /**
     * @param $adapterManager
     * @param ContainerBuilder $container
     */
    private function loadAdapterManager($adapterManager, ContainerBuilder $container)
    {
        $configDefTemplate = empty($adapterManager['configuration_id'])
                                ? 'doctrine_orm_phpcr_adapter.configuration'
                                : $adapterManager['configuration_id'];
        $configDef = $container->setDefinition(
            sprintf(
                'doctrine_orm_phpcr_adapter.adapter.%s_configuration',
                $adapterManager['name']
            ),
            new DefinitionDecorator($configDefTemplate)
        );

        $this->loadAdapterManagerMappingInformation($adapterManager, $configDef, $container);
        $this->loadAdapterCacheDrivers($adapterManager, $container);

        $methods = array(
            'setMetadataCacheImpl'        => array(
                new Reference(sprintf('doctrine_orm_phpcr_adapter.adapter.%s_metadata_cache', $adapterManager['name']))
            ),
            'setMetadataDriverImpl'       => array(
                new Reference('doctrine_orm_phpcr_adapter.adapter.' . $adapterManager['name'] . '_metadata_driver'),
                false
            ),
            'setProxyDir'                 => array('%doctrine_orm_phpcr_adapter.adapter.proxy_dir%'),
            'setProxyNamespace'           => array('%doctrine_orm_phpcr_adapter.adapter.proxy_namespace%'),
            'setAutoGenerateProxyClasses' => array('%doctrine_orm_phpcr_adapter.adapter.auto_generate_proxy_classes%'),
            'setClassMetadataFactoryName' => array($adapterManager['class_metadata_factory_name']),
            'setManagers'   => array($this->referenceManagers),
        );

        foreach ($methods as $method => $args) {
            $configDef->addMethodCall($method, $args);
        }

        $container
            ->setDefinition(
                $adapterManager['service_name'],
                new DefinitionDecorator('doctrine_orm_phpcr_adapter.adapter_manager.abstract')
            )
            ->setArguments(array(
                new Reference(sprintf('doctrine_orm_phpcr_adapter.adapter.%s_configuration', $adapterManager['name'])),
                new Reference('doctrine_orm_phpcr_adapter.adapter.event_manager')
            ))
        ;

    }

    /**
     * @param $adapterManager
     * @param $configDef
     * @param ContainerBuilder $container
     */
    private function loadAdapterManagerMappingInformation(
        $adapterManager,
        Definition $configDef,
        ContainerBuilder $container
    ) {
        $this->drivers = array();
        $this->aliasMap = array();
        $this->bundleDirs = array();

        $this->loadMappingInformation($adapterManager, $container);
        $this->registerMappingDrivers($adapterManager, $container);

        $configDef->addMethodCall('getObjectNamespaces', array($this->aliasMap));
    }

    /**
     * Loads a configured adapter managers cache drivers.
     *
     * @param array            $adapterManger A configured adapter manager.
     * @param ContainerBuilder $container     A ContainerBuilder instance
     */
    protected function loadAdapterCacheDrivers(array $adapterManger, ContainerBuilder $container)
    {
        $this->loadObjectManagerCacheDriver($adapterManger, $container, 'metadata_cache');
    }

    /**
     * Prefixes the relative dependency injection container path with the object manager prefix.
     *
     * @example $name is 'entity_manager' then the result would be 'doctrine.orm.entity_manager'
     *
     * @param string $name
     *
     * @return string
     */
    protected function getObjectManagerElementName($name)
    {
        return 'doctrine_orm_phpcr_adapter.adapter.'.$name;
    }

    /**
     * Noun that describes the mapped objects such as Entity or Document.
     *
     * Will be used for autodetection of persistent objects directory.
     *
     * @return string
     */
    protected function getMappingObjectDefaultName()
    {
        return 'Object';
    }

    /**
     * Relative path from the bundle root to the directory where mapping files reside.
     *
     * @return string
     */
    protected function getMappingResourceConfigDirectory()
    {
        return 'Resources/config/doctrine';
    }

    /**
     * Extension used by the mapping files.
     *
     * @return string
     */
    protected function getMappingResourceExtension()
    {
        return 'adapter';
    }
}
