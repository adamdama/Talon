<?php
namespace Talon\Utilities;

use \Phalcon\Mvc\User\Component;

/**
 * Talon\Utilities\Utilities
 * Various utility methods for use in Talon
 */
class Utilities extends Component
{
	public function camelSeparate($string, $separator = ' '){
		return ucwords(preg_replace("/(?=[A-Z])/", "$separator$1", $string));
	}
}