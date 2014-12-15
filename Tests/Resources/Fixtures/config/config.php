<?php
$container->loadFromExtension('doctrine_orm_phpcr_adapter', array(
    'managers' => array(
        'reference-phpcr'    => array('default' => 'doctrine_phpcr.odm.default_document_manager'),
        'reference-dbal-orm' => array('default' => 'doctrine.orm.default_entity_manager'),
    ),
    'adapter'  => array(
        'auto_mapping' => true,
        'auto_generate_proxy_classes' => true,
        'mappings' => array(
            'test_mapping' => array(
                'type' => 'annotation',
                'prefix' => '\Entity',
                'dir' => '%kernel.root_dir%/../Entity',
                'is_bundle' => false,
            ),
        ),
    ),
));
