<?php

use Phalcon\Mvc\Model\Validator\Email as Email,
	Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness,
	Phalcon\Mvc\Model\Message as Message;

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


	protected $confirmEmail;


	protected $confirmPassword;

	protected $columns = array(
		'id' => 'id',
		'name' => 'name',
		'email' => 'email',
		'password' => 'password',
		'created' => 'created',
		'modified' => 'modified'
	);

	public function save($data = null, $whiteList = null)
	{
		$data['created'] = $data['modified'] = new Phalcon\Db\RawValue('now()');

		$this->confirmEmail = $data['confirm_email'];
		$this->confirmPassword = $data['confirm_password'];

		if(!is_array($whiteList)) {
			$whiteList = array('name', 'email', 'password');
		}

		$whiteList[] = 'created';
		$whiteList[] = 'modified';

		return parent::save($data, $whiteList);
	}

	/**
     * Validations and business logic
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

	    if ($this->validationHasFailed() == true) {
		    $fail = true;
	    }

	    if (empty($this->password)) {
		    $text = 'Password cannot be empty';
		    $field = 'password';
		    $type = 'PresenceOf';
		    $message = new \Phalcon\Mvc\Model\Message($text, $field, $type);
		    $this->appendMessage($message);
		    $fail = true; //this will abort the operation
	    }

	    // check passwords match
	    if ($this->password != $this->confirmPassword) {
		    $message = new Message("Passwords must match", 'password', 'Confirmation');
		    $this->appendMessage($message);
		    $fail = true;
	    }

		//check provided emails match
	    if ($this->email != $this->confirmEmail) {
		    $message = new Message("Email address must match", 'email', 'Confirmation');
		    $this->appendMessage($message);
		    $fail = true;
	    }

	    if($fail)
		    return false; //this will abort the operation
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return $this->columns;
    }

}
