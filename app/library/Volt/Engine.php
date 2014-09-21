<?php
/**
 * Engine.php
 * @author Adam
 * @created 21/09/2014
 */

namespace Talon\Volt;

use \Phalcon\Mvc\View\Engine\Volt;

class Engine extends Volt {
	public function getCompiler()
	{
		if (empty($this->_compiler))
		{
			parent::getCompiler();

			// add macros that need initialized before parse time
			$this->partial("macros");
		}

		return parent::getCompiler();
	}
} 