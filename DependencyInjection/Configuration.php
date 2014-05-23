<?php

namespace Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\DependencyInjection;

use Doctrine\ORM\ODMAdapter\Reference;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('doctrine_orm_phpcr_adapter')
            ->children()
                ->arrayNode('managers')
                    ->beforeNormalization()
                    ->ifTrue(function ($v) {
                        return null === $v || (is_array($v));
                    })
                    ->then(function ($v) {
                        $managers = array();
                        $mapReferenceType = array(
                            'reference_phpcr' => Reference::PHPCR,
                            'reference_dbal_orm' => Reference::DBAL_ORM,
                        );
                        $availableReferenceTypes = array(Reference::DBAL_ORM, Reference::PHPCR);
                        foreach ($v as $typeInList => $managersInList) {
                            $type = null;
                            if (isset($mapReferenceType[$typeInList])) {
                                $type = $mapReferenceType[$typeInList];
                            } elseif (isset($availableReferenceTypes[$typeInList])) {
                                $type = $availableReferenceTypes[$typeInList];
                            }

                            if (null === $type) {
                                continue;
                            }

                            foreach ($managersInList as $name => $service) {
                                $managers[] = array(
                                    'name'    => $name,
                                    'service' => $service,
                                    'type'    => $type,
                                );
                            }
                        }

                        return $managers;
                    })
                    ->end()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('type')->isRequired()->end()
                            ->scalarNode('name')->isRequired()->end()
                            ->scalarNode('service')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('adapter')
                    ->beforeNormalization()
                    ->ifTrue(function ($v) {
                        return null === $v
                            || (is_array($v)
                            && !array_key_exists('adapter_managers', $v)
                            && !array_key_exists('adapter_manager', $v));
                    })
                    ->then(function ($v) {
                        $v = (array) $v;
                        // Key that should not be rewritten to the connection config
                        $excludedKeys = array(
                            'default_adapter_manager' => true,
                            'auto_generate_proxy_classes' => true,
                            'proxy_dir' => true,
                            'proxy_namespace' => true,
                        );
                        $adapterManagers = array();
                        foreach ($v as $key => $value) {
                            if (isset($excludedKeys[$key])) {
                                continue;
                            }
                            $adapterManagers[$key] = $v[$key];
                            unset($v[$key]);
                        }
                        $v['default_adapter_manager'] = isset($v['default_adapter_manager'])
                                                        ? (string) $v['default_adapter_manager']
                                                        : 'default';
                        $v['adapter_managers'] = array($v['default_adapter_manager'] => $adapterManagers);

                        return $v;
                    })
                    ->end()
                    ->children()
                        ->scalarNode('default_adapter_manager')->end()
                        ->booleanNode('auto_generate_proxy_classes')->defaultFalse()->end()
                        ->scalarNode('proxy_dir')->defaultValue('%kernel.cache_dir%/doctrine/PHPCRProxies')->end()
                        ->scalarNode('proxy_namespace')->defaultValue('PHPCRProxies')->end()
                    ->end()
                    ->fixXmlConfig('adapter_manager')
                    ->append($this->getAdapterManagersNode())
                ->end()
            ->end()
            ;

        return $treeBuilder;
    }

    private function getAdapterManagersNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('adapter_managers');

        $node
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->addDefaultsIfNotSet()
                ->append($this->getApapterCacheDriverNode('metadata_cache_driver'))
                ->children()
                    ->scalarNode('configuration_id')->end()
                    ->scalarNode('class_metadata_factory_name')->defaultValue('Doctrine\ORM\ODMAdapter\Mapping\ClassMetadataFactory')->end()
                    ->scalarNode('auto_mapping')->defaultFalse()->end()
                ->end()
                ->fixXmlConfig('mapping')
                ->children()
                    ->arrayNode('mappings')
                        ->useAttributeAsKey('name')
                        ->prototype('array')
                            ->beforeNormalization()
                                ->ifString()
                                ->then(function($v) { return array('type' => $v); })
                            ->end()
                            ->treatNullLike(array())
                            ->treatFalseLike(array('mapping' => false))
                            ->performNoDeepMerging()
                            ->children()
                                ->scalarNode('mapping')->defaultValue(true)->end()
                                ->scalarNode('type')->end()
                                ->scalarNode('dir')->end()
                                ->scalarNode('alias')->end()
                                ->scalarNode('prefix')->end()
                                ->booleanNode('is_bundle')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    private function getApapterCacheDriverNode($name)
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root($name);

        $node
            ->addDefaultsIfNotSet()
                ->beforeNormalization()
                ->ifString()
                ->then(function ($v) {
                        return array('type' => $v);
                })
            ->end()
            ->children()
            ->scalarNode('type')->defaultValue('array')->end()
                ->scalarNode('host')->end()
                ->scalarNode('port')->end()
                ->scalarNode('instance_class')->end()
                ->scalarNode('class')->end()
                ->scalarNode('id')->end()
            ->end();

        return $node;
    }
}
