<?php

$loader->import(__DIR__.'/default.php');
$loader->import(CMF_TEST_CONFIG_DIR.'/phpcr_odm.php');
$loader->import(CMF_TEST_CONFIG_DIR.'/doctrine_orm.php');
$loader->import(__DIR__.'/doctrine_orm_phpcr_adapter.yml');

$container->loadFromExtension('doctrine', array(
    'orm' => array(
        'mappings' => array(
            'tests_fixtures' => array(
                'type' => 'annotation',
                'prefix' => 'Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Tests\Resources\Entity',
                'dir' => $container->getParameter('kernel.root_dir').'/../Entity',
                'is_bundle' => false,
            ),
        ),
    ),
));
