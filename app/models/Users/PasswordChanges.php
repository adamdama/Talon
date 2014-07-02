<?php
namespace Talon\Models\Users;

use \Phalcon\Db\RawValue,
	\Phalcon\Mvc\Model,
	Talon\Models\ModelBase;

/**
 * PasswordChanges
 * Register when a user changes his/her password
 */
class PasswordChanges extends ModelBase
{
    public $usersId;

    public $ipAddress;

    public $userAgent;

    public $created;

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
		$this->belongsTo('usersId', 'Talon\Models\Users', 'id', array(
			'alias' => 'user'
		));
	}

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        // Timestamp the password change
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
