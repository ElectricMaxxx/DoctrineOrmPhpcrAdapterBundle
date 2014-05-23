<?php

$kernelRootDir = $container->getParameter('kernel.root_dir');
$bundleName = null;
if (preg_match('&/([a-zA-Z]+?)Bundle&', $kernelRootDir, $matches)) {
    $bundleName = $matches[1].'Bundle';
    $bundleFQN = 'Doctrine\\ORM\\Bundle\\'.$matches[1].'Bundle';
    if (!$container->hasParameter('cmf_testing.bundle_name')) {
        $container->setParameter('cmf_testing.bundle_name', $bundleName);
    }
    if (!$container->hasParameter('cmf_testing.bundle_fqn')) {
        $container->setParameter('cmf_testing.bundle_fqn', $bundleFQN);
    }
}

$loader->import(CMF_TEST_CONFIG_DIR.'/dist/parameters.yml');
$loader->import(CMF_TEST_CONFIG_DIR.'/dist/framework.yml');
if (class_exists('Symfony\Bundle\MonologBundle\MonologBundle')) {
    $loader->import(CMF_TEST_CONFIG_DIR.'/dist/monolog.yml');
}
$loader->import(CMF_TEST_CONFIG_DIR.'/dist/doctrine.yml');
$loader->import(CMF_TEST_CONFIG_DIR.'/dist/security.yml');

$config = array(
    'managers' => array(
        'reference-phpcr'    => array('default' => 'doctrine_phpcr.odm.default_document_manager'),
        'reference-dbal-orm' => array('default' => 'doctrine.orm.default_entity_manager'),
    ),
    'adapter'  => array(
        'auto_mapping' => true,
        'auto_generate_proxy_classes' => '%kernel.debug%',
        'mappings' => array(),
    ),
);
$kernelRootDir = $container->getParameter('kernel.root_dir');
$bundleFQN = $container->getParameter('cmf_testing.bundle_fqn');
$phpcrOdmDocDir = sprintf('%s/../Entity', $kernelRootDir);
$phpcrOdmDocPrefix = sprintf('%s\Tests\Resources\Entity', $bundleFQN);

if (file_exists($phpcrOdmDocDir)) {
    $config['adapter']['mappings']['test_additional'] = array(
        'type' => 'annotation',
        'prefix' => $phpcrOdmDocPrefix,
        'dir' => $phpcrOdmDocDir,
        'is_bundle' => false,
    );
}

$container->loadFromExtension('doctrine_orm_phpcr_adapter', $config);


/*
 * Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Tests\Resources\Entity\Object
 * Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Tests\Resources\Entity
 */
