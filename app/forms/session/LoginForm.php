<?php
namespace Talon\Forms\Session;

use \Talon\Forms\FormBase,
	\Phalcon\Forms\Element\Text,
	\Phalcon\Forms\Element\Password,
	\Phalcon\Forms\Element\Submit,
	\Phalcon\Forms\Element\Check,
	\Phalcon\Validation\Validator\PresenceOf,
	\Phalcon\Validation\Validator\Email;

class LoginForm extends FormBase {

	public function initialize() {
		parent::initialize();

		// Email
		$email = new Text('email');
		$email->setLabel('Email');

		$email->addValidators(array(
			new PresenceOf(array(
				'message' => 'The e-mail is required'
			)),
			new Email(array(
				'message' => 'The e-mail is not valid'
			))
		));

		$this->add($email);

		// Password
		$password = new Password('password');
		$password->setLabel('Password');

		$password->addValidators(array(
			new PresenceOf(array(
				'message' => 'The password is required'
			))
		));

		$this->add($password);

		// Remember
		$remember = new Check('remember', array(
			'value' => 'yes'
		));
		$remember->setLabel('Remember me');

		$this->add($remember);

		// Sign Up
		$this->add(new Submit('Login', array(
			'class' => 'btn btn-success'
		)));
	}
} 