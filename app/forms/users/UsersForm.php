<?php
namespace Talon\Forms\Users;

use Phalcon\Forms\Element\Submit;
use Talon\Forms\FormBase,
	\Phalcon\Forms\Element\Text,
	\Phalcon\Forms\Element\Hidden,
	\Phalcon\Forms\Element\Select,
	\Phalcon\Forms\Element\Password,
	\Phalcon\Validation\Validator\PresenceOf,
	\Phalcon\Validation\Validator\Email,
	\Phalcon\Validation\Validator\StringLength;

class UsersForm extends FormBase {

	public function initialize($entity = null, $options = null)	{
		parent::initialize();

		// In edition the id is hidden
		if (isset($options['edit']) && $options['edit']) {
			$id = new Hidden('id');

			$id->addValidators(array(
				new PresenceOf(array(
					'message' => 'ID missing'
				))
			));
			$this->add($id);
		} else {
			// Password
			$password = new Password('password', array(
				'placeholder' => 'Password'
			));
			$password->setLabel('Password');

			$password->addValidators(array(
				new PresenceOf(array(
					'message' => 'The password is required'
				)),
				new StringLength(array(
					'min' => 8,
					'messageMinimum' => 'Password is too short. Minimum 8 characters'
				))
			));

			$this->add($password);
		}

		$name = new Text('name', array(
			'placeholder' => 'Name'
		));
		$name->setLabel('Name');

		$name->addValidators(array(
			new PresenceOf(array(
				'message' => 'The name is required'
			))
		));

		$this->add($name);

		$email = new Text('email', array(
			'placeholder' => 'Email'
		));
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

		$this->add(new Select('validated', array(
			'1' => 'Yes',
			'0' => 'No'
		)));

		$this->add(new Select('active', array(
			'1' => 'Yes',
			'0' => 'No'
		)));

		$this->add(new Submit('Save'));
	}
}