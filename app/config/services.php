<?php

use \Phalcon\DI\FactoryDefault,
	\Phalcon\Mvc\View,
	\Phalcon\Mvc\Url as UrlResolver,
	\Phalcon\Mvc\Dispatcher as Dispatcher,
	\Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter,
	\Phalcon\Mvc\View\Engine\Volt as VoltEngine,
	\Phalcon\Session\Adapter\Files as SessionAdapter,
	\Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter,
	\Talon\Acl\Acl,
	\Phalcon\Security,
	\Phalcon\Flash\Direct,
	\Talon\Auth\Auth,
	\Talon\Mail\Mail,
	\Talon\Utilities\Utilities;

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
        'dbname' => $config->database->dbname,
	    'options' => array(
		    PDO::ATTR_EMULATE_PREPARES => false,
		    PDO::ATTR_STRINGIFY_FETCHES => false,
	    )
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
 * Custom authentication component
 */
$di->set('auth', function () {
	return new Auth();
});

/**
 * Custom utilities component
 */
$di->set('utilities', function () {
	return new Utilities();
});

/**
 * Custom mail component
 */
$di->set('mail', function () {
	return new Mail();
});

/**
 * Custom mail component
 */
$di->set('acl', function () {
	return new Acl();
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
 * Set the encryption service
 * Key: 7}T/~"4%[GW*7O-)!"nU
 */
$di->setShared('crypt', function() {
	$crypt = new \Phalcon\Crypt();
	$crypt->setMode(MCRYPT_MODE_CFB);
	$crypt->setKey('7}T/~"4%[GW*7O-)!"nU');
	return $crypt;
});

/**
 * Set global access to config, excluding db settings
 */
$di->set('config', $config);

$di->get('auth');