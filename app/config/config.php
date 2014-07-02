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
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir'      => __DIR__ . '/../../app/models/',
        'viewsDir'       => __DIR__ . '/../../app/views/',
	    'formsDir'       => __DIR__ . '/../../app/forms/',
        'pluginsDir'     => __DIR__ . '/../../app/plugins/',
        'libraryDir'     => __DIR__ . '/../../app/library/',
        'cacheDir'       => __DIR__ . '/../../app/cache/',
	    'incubatorDir'   => __DIR__ . '/../../vendor/phalcon/incubator/Library/Phalcon/',
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
