<?php
namespace Talon\Forms\Session;

use \Talon\Forms\FormBase,
	\Phalcon\Forms\Element\Text,
	\Phalcon\Forms\Element\Password,
	\Phalcon\Forms\Element\Submit,
	//\Phalcon\Forms\Element\Check,
	\Phalcon\Validation\Validator\PresenceOf,
	\Phalcon\Validation\Validator\Email,
	//\Phalcon\Validation\Validator\Identical,
	\Phalcon\Validation\Validator\StringLength,
	\Phalcon\Validation\Validator\Confirmation;

class SignUpForm extends FormBase {

	public function initialize() {
		parent::initialize();

		$name = new Text('name');
		$name->setLabel('Name');

		$name->addValidators(array(
			new PresenceOf(array(
				'message' => 'The name is required'
			))
		));

		$this->add($name);

		// Email
		$email = new Text('email');
		$email->setLabel('Email');

		$email->addValidators(array(
			new PresenceOf(array(
				'message' => 'The e-mail is required'
			)),
			new Email(array(
				'message' => 'The e-mail is not valid'
			)),
			new Confirmation(array(
				'message' => 'Email doesn\'t match confirmation',
				'with' => 'confirmEmail'
			))
		));

		$this->add($email);

		// Confirm Email
		$confirmEmail = new Text('confirmEmail');
		$confirmEmail->setLabel('Confirm Email');

		$confirmEmail->addValidators(array(
			new PresenceOf(array(
				'message' => 'The confirmation e-mail is required'
			))
		));

		$this->add($confirmEmail);

		// Password
		$password = new Password('password');
		$password->setLabel('Password');

		$password->addValidators(array(
			new PresenceOf(array(
				'message' => 'The password is required'
			)),
			new StringLength(array(
				'min' => 8,
				'messageMinimum' => 'Password is too short. Minimum 8 characters'
			)),
			new Confirmation(array(
				'message' => 'Password doesn\'t match confirmation',
				'with' => 'confirmPassword'
			))
		));

		$this->add($password);

		// Confirm Password
		$confirmPassword = new Password('confirmPassword');
		$confirmPassword->setLabel('Confirm Password');

		$confirmPassword->addValidators(array(
			new PresenceOf(array(
				'message' => 'The confirmation password is required'
			))
		));

		$this->add($confirmPassword);

		// Remember
//		$terms = new Check('terms', array(
//			'value' => 'yes'
//		));//
//		$terms->setLabel('Accept terms and conditions');
//
//		$terms->addValidator(new Identical(array(
//			'value' => 'yes',
//			'message' => 'Terms and conditions must be accepted'
//		)));
//
//		$this->add($terms);

		// Sign Up
		$this->add(new Submit('Sign Up', array(
			'class' => 'btn btn-success'
		)));
	}
} 