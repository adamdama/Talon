<?php
namespace Talon\Forms\Users;

use Talon\Forms\FormBase;
//	\Phalcon\Forms\Element\Text,
//	\Phalcon\Forms\Element\Hidden,
//	\Phalcon\Forms\Element\Select,
//	\Phalcon\Validation\Validator\PresenceOf,
//	\Phalcon\Validation\Validator\Email;
//
class NewUserForm extends FormBase {
//
//	public function initialize($entity = null, $options = null)
//	{
//
//		// In edition the id is hidden
//		if (isset($options['edit']) && $options['edit']) {
//			$id = new Hidden('id');
//		} else {
//			$id = new Text('id');
//		}
//
//		$this->add($id);
//
//		$name = new Text('name', array(
//			'placeholder' => 'Name'
//		));
//
//		$name->addValidators(array(
//			new PresenceOf(array(
//				'message' => 'The name is required'
//			))
//		));
//
//		$this->add($name);
//
//		$email = new Text('email', array(
//			'placeholder' => 'Email'
//		));
//
//		$email->addValidators(array(
//			new PresenceOf(array(
//				'message' => 'The e-mail is required'
//			)),
//			new Email(array(
//				'message' => 'The e-mail is not valid'
//			))
//		));
//
//		$this->add($email);
//
//		$this->add(new Select('profilesId', Profiles::find('active = "Y"'), array(
//			'using' => array(
//				'id',
//				'name'
//			),
//			'useEmpty' => true,
//			'emptyText' => '...',
//			'emptyValue' => ''
//		)));
//
//		$this->add(new Select('banned', array(
//			'Y' => 'Yes',
//			'N' => 'No'
//		)));
//
//		$this->add(new Select('suspended', array(
//			'Y' => 'Yes',
//			'N' => 'No'
//		)));
//
//		$this->add(new Select('active', array(
//			'Y' => 'Yes',
//			'N' => 'No'
//		)));
//	}
}