<?php
/*
 * Define custom routes. File gets included in the router service definition.
 */
$router = new \Phalcon\Mvc\Router();

//Remove trailing slashes automatically
$router->removeExtraSlashes(true);

$router->add('/([a-zA-Z\-]+)/([a-zA-Z\-]+)/:params', array(
	'controller' => 1,
	'action' => 2,
	'params' => 3
))->convert('action', function($action) {
	return Phalcon\Text::camelize($action);
});

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

$router->add('/reset-password/{code}/{email}', array(
	'controller' => 'users_control',
	'action' => 'resetPassword'
));

$router->add('/confirm/{code}/{email}', array(
	'controller' => 'users_control',
	'action' => 'confirmEmail'
));

$router->add('/resend-confirmation/{email}', array(
	'controller' => 'users_control',
	'action' => 'resendConfirmation'
));

return $router;