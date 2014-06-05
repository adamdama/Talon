<?php
/*
 * Define custom routes. File gets included in the router service definition.
 */
$router = new \Phalcon\Mvc\Router();

$router->add('/signup/', array(
	'namespace' => 'Talon\Controllers',
	'controller' => 'session',
	'action' => 'signup'
));

$router->add('/thank-you/', array(
	'namespace' => 'Talon\Controllers',
	'controller' => 'session',
	'action' => 'registered'
));

return $router;