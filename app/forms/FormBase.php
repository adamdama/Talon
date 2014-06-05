<?php
namespace Talon\Forms;

use \Phalcon\Forms\Form,
	\Phalcon\Forms\Element\Hidden,
	\Phalcon\Validation\Validator\Identical;

class FormBase extends Form {
	/**
	 * @var $security \Phalcon\Security
	 */

	public function initialize() {
		$csrf = new Hidden('csrf');

		$csrf->addValidator(new Identical(array(
			'value' => $this->security->getSessionToken(),
			'message' => 'CSRF validation failed'
		)));

		$this->add($csrf);
	}

	/**
	 * Prints messages for a specific element
	 */
	public function messages($name)
	{
		if ($this->hasMessagesFor($name)) {
			foreach ($this->getMessagesFor($name) as $message) {
				$this->flashSession->error($message);
			}
		}
	}

} 