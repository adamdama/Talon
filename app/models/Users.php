<?php

use Phalcon\Mvc\Model\Validator\Email as Email,
	Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness,
	Phalcon\Mvc\Model\Validator\ConfirmationOf as ConfirmationOf,
	Phalcon\Mvc\Model\Validator\PresenceOf as PresenceOf;

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
	 * @var string
	 */
	public $confirmEmail;


	/**
	 * @var string
	 */
	public $confirmPassword;

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
		$this->modified = new Phalcon\Db\RawValue('now()');
	}

	/**
	 * Before we validate the data we need to generate a modified date
	 */
	public function beforeValidationOnCreate() {
		$this->created = new Phalcon\Db\RawValue('now()');
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

	    $this->validate(
		    new PresenceOf(
			    array(
				    'field' => 'confirmEmail',
				    'message' => 'Confirmation email must be provided'
			    )
		    )
	    );

	    $this->validate(
		    new ConfirmationOf(
			    array(
				    'field' => 'email',
				    'field_confirmation' => 'confirmEmail',
				    'message' => 'Emails must match'
			    )
		    )
	    );

	    $this->validate(
		    new PresenceOf(
			    array(
				    'field' => 'confirmPassword',
				    'message' => 'Confirmation password must be provided'
			    )
		    )
	    );

	    $this->validate(
		    new ConfirmationOf(
			    array(
				    'field' => 'password',
				    'field_confirmation' => 'confirmPassword',
				    'message' => 'Passwords must match'
			    )
		    )
	    );

	    if ($this->validationHasFailed() === true)
		    return false; //this will abort the operation
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
