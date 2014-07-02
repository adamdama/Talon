<?php
namespace Talon\Models\Users;

use Talon\Models\ModelBase,
	Phalcon\Db\RawValue;

/**
 * ResetPasswords
 * Stores the reset password codes and their evolution
 */
class ResetPasswords extends ModelBase
{
	public $usersId;

	public $code;

	protected $created;

	protected $modified;

	public $reset;

	/**
	 * Independent Column Mapping.
	 *
	 * @return array
	 */
	public function columnMap()
	{
		return array(
			'id' => 'id',
			'users_id' => 'usersId',
			'code' => 'code',
			'created' => 'created',
			'modified' => 'modified',
			'reset' => 'reset'
		);
	}

	public function initialize()
	{
		$this->belongsTo('usersId', 'Talon\Models\Users\Users', 'id', array(
			'alias' => 'user'
		));
	}

	/**
	 * Before create the user assign a password
	 */
	public function beforeValidationOnCreate()
	{
		// Timestamp the confirmation
		$this->created = new RawValue('now()');

		// Generate a random confirmation code
		$this->code = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(24)));

		// Set status to non-confirmed
		$this->reset = 0;
	}

	/**
	 * Sets the timestamp before update the confirmation
	 */
	public function beforeValidation()
	{
		// Timestamp the confirmation
		$this->modified = new RawValue('now()');
	}

	/**
	 * Send an e-mail to users allowing him/her to reset his/her password
	 */
	public function afterCreate()
	{
		$this->getDI()->getMail()->send(
			'Reset your password',
			'resetPassword',
			array(
				'name' => $this->user->name,
				'resetUrl' => '/reset-password/' . $this->code . '/' . $this->user->email
			),
			array(
				$this->user->email => $this->user->name
			)
		);
	}
}