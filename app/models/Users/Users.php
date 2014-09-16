<?php
namespace Talon\Models\Users;

use \Phalcon\Mvc\Model\Validator\Email,
	\Phalcon\Mvc\Model\Validator\Uniqueness,
	\Phalcon\Db\RawValue,
	\Talon\Models\ModelBase;

/**
 * Class Users
 */
class Users extends ModelBase
{
	const USER_DOES_NOT_EXIST = 'User does not exist';

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
    protected $password;

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
		$this->hasMany('id', 'Talon\Models\Users\SuccessLogins', 'usersId', array(
			'alias' => 'successLogins',
			'foreignKey' => array(
				'message' => 'User cannot be deleted because he/she has activity in the system'
			)
		));

		$this->hasMany('id', 'Talon\Models\Users\PasswordChanges', 'usersId', array(
			'alias' => 'passwordChanges',
			'foreignKey' => array(
				'message' => 'User cannot be deleted because he/she has activity in the system'
			)
		));

		$this->hasMany('id', 'Talon\Models\Users\ResetPasswords', 'usersId', array(
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
		$this->active = 0;
		$this->validated = 0;
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
	                'message'  => 'Invalid email address'
                )
            )
        );

	    $this->validate(
		    new Uniqueness(
			    array(
				    'field'    => 'email',
				    'message'  => 'Email address already registered'
			    )
		    )
	    );

	    if ($this->validationHasFailed() === true)
		    return false; //this will abort the operation

	    return true;
    }

	public function setPassword($password) {
		$this->password = $this->encryptPassword($password);
	}

	/**
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}



	public function sendConfirmation() {
		$emailConfirmation = new EmailConfirmations();
		$emailConfirmation->usersId = $this->id;

		if(!$emailConfirmation->save()) {
			foreach($emailConfirmation->getMessages() as $message) {
				$this->appendMessage($message);
			}

			return false;
		}

		return true;
	}

	/**
	 * Send a confirmation e-mail to the user if the account is not validated
	 */
	public function afterSave()
	{
		//annoying on every edit, move to a checkbox on form and handle in controller
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
}
