<?php

$loader = new \Phalcon\Loader();

/**
 * Registering the Incubator namespace so that we can load classes from it
 * We're also registering a set of directories taken from the configuration file
 */
$loader->registerNamespaces(array(
	'Phalcon' => $config->application->incubatorDir,
	'Talon\Models' => $config->application->modelsDir,
	'Talon\Controllers' => $config->application->controllersDir,
	'Talon\Forms' => $config->application->formsDir,
	'Talon' => $config->application->libraryDir
));

$loader->register();