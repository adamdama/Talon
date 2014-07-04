<?php
namespace Talon\Models\Users;

use Talon\Models\ModelBase,
	\Phalcon\Db\RawValue;

/**
 * FailedLogins
 * This model registers unsuccessful logins registered and non-registered users have made
 */
class FailedLogins extends ModelBase
{
	public $usersId;

	public $ipAddress;

	public $userAgent;

	protected $created;

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
			'ip_address' => 'ipAddress',
			'user_agent' => 'userAgent',
			'created' => 'created'
		);
	}

	public function initialize()
	{
		$this->setSource('failed_logins');

		$this->belongsTo('usersId', 'Talon\Models\Users\Users', 'id', array(
			'alias' => 'user'
		));
	}

	public function beforeValidationOnCreate() {
		$this->created = new RawValue('now()');
	}

	/**
	 * Convert IP address to a number for efficient storage
	 */
	public function beforeSave() {
		$this->ipAddress = inet_pton($this->ipAddress);
	}

	/**
	 * Convert IP address to a readable format
	 */
	public function afterSave() {
		$this->ipAddress = inet_ntop($this->ipAddress);
	}

	/**
	 * Convert IP address to a readable format
	 */
	public function afterFetch() {
		$this->ipAddress = inet_ntop($this->ipAddress);
	}
}