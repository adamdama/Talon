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
	public $name;

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
    protected $created;

	/**
	 *
	 * @var string
	 */
	protected $modified;

	/**
	 *
	 * @var int
	 */
	public $validated;

	/**
	 *
	 * @var int
	 */
	public $active;

	/**
	 * Boot up the model and set some default settings
	 */
	public function initialize()
	{
		$this->hasMany('id', 'Talon\Models\SuccessLogins', 'usersId', array(
			'alias' => 'successLogins',
			'foreignKey' => array(
				'message' => 'User cannot be deleted because he/she has activity in the system'
			)
		));

		$this->hasMany('id', 'Talon\Models\PasswordChanges', 'usersId', array(
			'alias' => 'passwordChanges',
			'foreignKey' => array(
				'message' => 'User cannot be deleted because he/she has activity in the system'
			)
		));

		$this->hasMany('id', 'Talon\Models\ResetPasswords', 'usersId', array(
			'alias' => 'resetPasswords',
			'foreignKey' => array(
				'message' => 'User cannot be deleted because he/she has activity in the system'
			)
		));
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
			'modified' => 'modified',
			'validated' => 'validated',
			'active' => 'active'
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
		$this->active = 1;
		$this->validated = 1;
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
		$password = $this->getDI()
			->getSecurity()
			->hash($password);

		return $password;
	}

//	/**
//	 *
//	 *
//	 * @param $email
//	 * @return bool|\Talon\Models\Users
//	 */
//	public static function findFirstByEmail($email) {
//		if(!is_string($email))
//			return false;
//
//		return self::findFirst(array("email='{$email}'"));
//	}
}
