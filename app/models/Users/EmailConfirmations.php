<?php
namespace Talon\Models\Users;

use \Phalcon\Mvc\Model,
	\Phalcon\Db\RawValue,
	Talon\Models\ModelBase;

/**
 * EmailConfirmations
 * Stores the reset password codes and their evolution
 */
class EmailConfirmations extends ModelBase
{
    public $usersId;

    public $code;

    public $created;

    public $modified;

    public $confirmed;

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
			'confirmed' => 'confirmed'
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
        $this->confirmed = 0;

	    echo $this->usersId;

	    // disable all other email confirmations
	    $emailConfirmations = EmailConfirmations::find(array(
		    'usersId = ?1',
		    'bind' => array(1 => $this->usersId)
		    )
	    );
	    /** @var EmailConfirmations $emailConfirmation */
	    foreach ($emailConfirmations as $emailConfirmation) {
		    $emailConfirmation->confirmed = 1;
		    $emailConfirmation->save();
	    }
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
     * Send a confirmation e-mail to the user after create the account
     */
    public function afterCreate()
    {
        $this->getDI()->getMail()->send(
	        'Please confirm your email',
	        'emailConfirmation',
	        array(
		        'name' => $this->user->name,
		        'confirmUrl' => '/confirm/' . $this->code . '/' . $this->user->email
	        ),
	        array(
	            $this->user->email => $this->user->name
	        )
        );
    }
}
