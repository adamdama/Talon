<?php

include(APP_DIR . '/config/private.php');

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => $private['database']['adapter'],
        'host'        => $private['database']['host'],
        'username'    => $private['database']['username'],
        'password'    => $private['database']['password'],
        'dbname'      => $private['database']['dbname'],
    ),
    'application' => array(
        'controllersDir' => APP_DIR . '/controllers/',
        'modelsDir'      => APP_DIR . '/models/',
        'viewsDir'       => APP_DIR . '/views/',
	    'formsDir'       => APP_DIR . '/forms/',
        'pluginsDir'     => APP_DIR . '/plugins/',
        'libraryDir'     => APP_DIR . '/library/',
        'cacheDir'       => APP_DIR . '/cache/',
	    'incubatorDir'   => BASE_DIR . '/vendor/phalcon/incubator/Library/Phalcon/',
        'baseUri'        => '/Talon/',
	    'publicUrl'      => 'http://webdev/Talon'
    ),
	'mail' => array(
		'smtp' => array(
			'server' => $private['mail']['smtp']['server'],
			'port' => $private['mail']['smtp']['port'],
			'security' => $private['mail']['smtp']['security'],
			'username' => $private['mail']['smtp']['username'],
			'password' => $private['mail']['smtp']['password']
		),
		'senders' => array(
			'default' => array('noreply@web.dev' => 'Talon')
		)
	),
	'errors' => array(
		'users' => array(
			'emailRequired'     => 'Email address is required',
			'emailInvalid'        => 'Must be a valid email address',
			'emailMustMatch'    => 'Emails must match'
		)
	)
));
