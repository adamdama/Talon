<?php
namespace Talon\Auth;

use \Phalcon\Exception;

class AuthException extends Exception
{
	const CREDENTIALS_FAILED = 'Incorrect email/password';
	const USER_DOES_NOT_EXIST = 'The user does not exist';
	const USER_NOT_ACTIVE = 'The user is inactive';
	const USER_NOT_VALIDATED = 'The user is not validated';
}