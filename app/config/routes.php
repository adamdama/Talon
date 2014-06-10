<?php
/*
 * Define custom routes. File gets included in the router service definition.
 */
$router = new \Phalcon\Mvc\Router();

//Remove trailing slashes automatically
$router->removeExtraSlashes(true);

$router->add('/signup', array(
	'namespace' => 'Talon\Controllers',
	'controller' => 'session',
	'action' => 'signup'
));

$router->add('/login', array(
	'namespace' => 'Talon\Controllers',
	'controller' => 'session',
	'action' => 'login'
));

return $router;