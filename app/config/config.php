<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'talon_user',
        'password'    => 'password',
        'dbname'      => 'talon',
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
			'server' => 'localhost',
			'port' => 25,
			'security' => null,
			'username' => '',
			'password' => ''
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
