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
