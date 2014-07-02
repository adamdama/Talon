<?php
namespace Talon\Mail;

use \Phalcon\Exception;

class MailException extends Exception {
	const NO_CONFIG_SET = 'There is now mail section in the config file.';
} 