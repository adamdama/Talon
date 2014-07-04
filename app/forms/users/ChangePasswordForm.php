<?php
namespace Talon\Forms\Users;

use \Talon\Forms\FormBase,
	\Phalcon\Forms\Element\Password,
	\Phalcon\Forms\Element\Submit,
	\Phalcon\Validation\Validator\PresenceOf,
	\Phalcon\Validation\Validator\StringLength,
	\Phalcon\Validation\Validator\Confirmation;

class ChangePasswordForm extends FormBase {

	public function initialize() {
		parent::initialize();

		// Password
		$password = new Password('password', array(
			'placeholder' => 'Password'
		));
		$password->setLabel('New Password');

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
		$confirmPassword = new Password('confirmPassword', array(
			'placeholder' => 'Confirm Password'
		));
		$confirmPassword->setLabel('Confirm New Password');

		$confirmPassword->addValidators(array(
			new PresenceOf(array(
				'message' => 'The confirmation password is required'
			))
		));

		$this->add($confirmPassword);

		// Sign Up
		$this->add(new Submit('Change Password', array(
			'class' => 'btn btn-success'
		)));
	}
} 