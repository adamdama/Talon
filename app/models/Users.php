<?php
namespace Talon\Models;

use \Phalcon\Mvc\Model\Validator\Email,
	\Phalcon\Mvc\Model\Validator\Uniqueness,
	\Phalcon\Db\RawValue;

/**
 * Class Users
 */
class Users extends ModelBase
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $created;

    /**
     *
     * @var string
     */
    public $modified;

	/**
	 * Boot up the model and set some default settings
	 */
	public function initialize()
	{
		// there once was something here
	}

	/**
	 * Independent Column Mapping.
	 *
	 * @return array
	 */
	public function columnMap()
	{
		return array(
			'id' => 'id',
			'name' => 'name',
			'email' => 'email',
			'password' => 'password',
			'created' => 'created',
			'modified' => 'modified'
		);
	}

	/**
	 * Before first time record creation we need to secure the password
	 */
	public function beforeCreate() {
		$this->password = $this->encryptPassword($this->password);
	}

	/**
	 * Before we validate the data we need to generate a modified date
	 */
	public function beforeValidation() {
		$this->modified = new RawValue('now()');
	}

	/**
	 * Before we validate the data we need to generate a modified date
	 */
	public function beforeValidationOnCreate() {
		$this->created = new RawValue('now()');
	}

	/**
	 * Validate the passed in data
	 *
	 * @return bool
	 */
	public function validation()
    {
        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
	                'message'  => 'email must be valid email format'
                )
            )
        );

	    $this->validate(
		    new Uniqueness(
			    array(
				    'field'    => 'email',
				    'message'  => 'email already registered'
			    )
		    )
	    );

	    if ($this->validationHasFailed() === true)
		    return false; //this will abort the operation

	    return true;
    }

	/**
	 * Encrypt the password (for Merlijn)
	 */
	protected function encryptPassword($password) {
		// get the security service and hash
		/** @noinspection PhpUndefinedMethodInspection */
		$password = $this->getDI()
			->getSecurity()
			->hash($password);

		return $password;
	}
}
