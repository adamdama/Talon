<?php
namespace Talon\Forms;
/** TODO implement hideen form validation into all forms */
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
			'message' => 'Security token validation failed'
		)));

		$this->add($csrf);
	}
	
 	/**
	 * Prints message for specific form element
	 *
     * @author David Kane <@daveykane>
	 * @access public
	 * @param String $name
	 * @return String
	 */
	public function message($name) {
		if($this->hasMessagesFor($name)) {
			foreach($this->getMessagesFor($name) as $message) {
				return $message;
			}
		}

		return false;
	}
	
 	/**
	 * Returns array of messages for specific form element
	 *
     * @author David Kane <@daveykane>
	 * @access public
	 * @param String $name
	 * @return Array
	 */
	public function messages($name) {
		if($this->hasMessagesFor($name)) {
			return $this->getMessagesFor($name);
		}

		return false;
	}
} 
