<?php
namespace Talon\Utilities;

use \Phalcon\Mvc\User\Component;

/**
 * Talon\Utilities\Utilities
 * Manages Authentication/Identity Management in Talon
 */
class Utilities extends Component
{
	public function camelSeparate($string, $separator = ' '){
		return ucwords(preg_replace("/(?=[A-Z])/", "$separator$1", $string));
	}
}