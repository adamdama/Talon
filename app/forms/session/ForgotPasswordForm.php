<?php
namespace Talon\Forms\Session;

use \Talon\Forms\FormBase,
	\Phalcon\Forms\Element\Text,
	\Phalcon\Forms\Element\Submit,
	\Phalcon\Validation\Validator\PresenceOf,
	\Phalcon\Validation\Validator\Email;

class ForgotPasswordForm extends FormBase {

	public function initialize() {
		parent::initialize();

		// Email
		$email = new Text('email', array('placeholder' => 'Email'));
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

		// Sign Up
		$this->add(new Submit('Send', array(
			'class' => 'btn btn-success'
		)));
	}
} 