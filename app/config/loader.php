<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    array(
        $config->application->controllersDir,
        $config->application->modelsDir
    )
)->register();

/**
 * Registering the Incubator namespace so that we can load classes from it
 */
$loader->registerNamespaces(array(
	'Phalcon' => dirname(__FILE__).'/../../vendor/phalcon/incubator/Library/Phalcon/'
));

$loader->register();