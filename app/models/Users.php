<?php

use Phalcon\Mvc\Model\Validator\Email as Email,
	Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness,
	Phalcon\Mvc\Model\Message as Message;

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
	 * @var bool
	 */
	protected $hashPassword = false;

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
	 * Write data to the db record
	 *
	 * @param null|array $data
	 * @param null|array $whiteList
	 * @return bool
	 */
	public function save($data = null, $whiteList = null)
	{
		// if this is the first time the user is being saved then we need to hash  the password
		if(empty($this->password)) {
			$this->hashPassword = true;
		} else {
			//ensure validation doesn't fire after first save
			$this->confirmPassword = null;
			$this->confirmEmail = null;
		}

		$this->modified = new Phalcon\Db\RawValue('now()');

		return parent::save($data, $whiteList);
	}


	/**
	 * Validate the passed in data
	 *
	 * @return bool
	 */
	public function validation()
    {

	    $fail = false;

        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
	                'message'  => 'email must be unique'
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

	    if ($this->validationHasFailed() === true) {
		    $fail = true;
	    }

	    // check passwords match
	    if (!empty($this->confirmPassword) && $this->password !== $this->confirmPassword) {
		    $message = new Message("Passwords must match", 'password', 'Confirmation');
		    $this->appendMessage($message);
		    $fail = true;
	    }

		//check provided emails match
	    if (!empty($this->confirmEmail) && $this->email !== $this->confirmEmail) {
		    $message = new Message("Email address must match", 'email', 'Confirmation');
		    $this->appendMessage($message);
		    $fail = true;
	    }

	    if($fail)
		    return false; //this will abort the operation

	    // if successful hash password
	    if($this->hashPassword)
	        $this->password = $this->encryptPassword($this->password);
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
