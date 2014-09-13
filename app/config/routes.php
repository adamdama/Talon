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
))->setName('session-signup');

$router->add('/login', array(
	'namespace' => 'Talon\Controllers',
	'controller' => 'session',
	'action' => 'login'
))->setName('session-login');

$router->add('/forgot-password', array(
	'namespace' => 'Talon\Controllers',
	'controller' => 'session',
	'action' => 'forgotPassword'
))->setName('session-forgotPassword');

$router->add('/reset-password/{code}/{email}', array(
	'controller' => 'users_control',
	'action' => 'resetPassword'
))->setName('session-resetPassword');

$router->add('/confirm/{code}/{email}', array(
	'controller' => 'users_control',
	'action' => 'confirmEmail'
))->setName('users_control-confirmEmail');

$router->add('/resend-confirmation/{email}', array(
	'controller' => 'users_control',
	'action' => 'resendConfirmation'
))->setName('users_control-resendConfirmation');

return $router;