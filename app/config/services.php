<?php

use \Phalcon\DI\FactoryDefault,
	\Phalcon\Mvc\View,
	\Phalcon\Mvc\Url as UrlResolver,
	\Phalcon\Mvc\Dispatcher as Dispatcher,
	\Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter,
	\Phalcon\Mvc\View\Engine\Volt as VoltEngine,
	/** @noinspection PhpUnusedAliasInspection */
	\Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter,
	\Phalcon\Session\Adapter\Files as SessionAdapter,
	\Phalcon\Security as Security,
	\Phalcon\Flash\Direct as Direct;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

/**
 * Registering a dispatcher
 */
$di->set('dispatcher', function() {
	$dispatcher = new Dispatcher();
	$dispatcher->setDefaultNamespace('Talon\Controllers');
	return $dispatcher;
});

/**
 * Loading routes from the routes.php file
 */
$di->set('router', function () {
	return require __DIR__ . '/routes.php';
});

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions(array(
                'compiledPath' => $config->application->cacheDir . 'volt/',
                'compiledSeparator' => '_',
	            'compileAlways' => true // turns off caching
            ));

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
}, true);

/**
 * Database connection is created based on the parameters defined in the configuration file
 */
$di->set('db', function () use ($config) {
    return new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname
    ));
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () use ($config) {
	return new MetaDataAdapter(array(
		'metaDataDir' => $config->application->cacheDir . 'metaData/'
	));
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

/**
 * Set up the flash messaging service
 */
$di->set('flash', function() {
	return new Direct();
});

/**
 * Set up the security service
 */
$di->set('security', function(){

	$security = new Security();

	//Set the password hashing factor to 12 rounds
	$security->setWorkFactor(12);

	return $security;
}, true);

/**
 * Set global access to config, excluding db settings
 */
$di->set('config', $config);