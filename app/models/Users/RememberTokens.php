<?php
namespace Talon\Models\Users;

use Talon\Models\ModelBase,
	\Phalcon\Db\RawValue,
	\Phalcon\Http\Response\Cookies;

/**
 * RememberTokens
 * Stores the remember me tokens
 */
class RememberTokens extends ModelBase
{
	/* Expiry time for cookies 8 days (8 * 86400) */
	const COOKIE_EXPIRY = 691200;

	const USER_COOKIE_KEY = 'RMU';

	const TOKEN_COOKIE_KEY = 'RMT';

	public $usersId;

	public $token;

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
			'token' => 'token',
			'user_agent' => 'userAgent',
			'created' => 'created'
		);
	}

	public function beforeValidationOnCreate()
	{
		$this->created = new RawValue('now()');
	}

	public function initialize()
	{
		$this->belongsTo('usersId', 'Talon\Models\Users', 'id', array(
			'alias' => 'user'
		));
	}

	public function isExpired() {
		return RememberTokens::COOKIE_EXPIRY < strtotime($this->created);
	}

	public static function setCookies(Cookies $cookies, $userValue, $tokenValue) {
		$cookies->set(RememberTokens::USER_COOKIE_KEY, $userValue, time() + RememberTokens::COOKIE_EXPIRY);
		$cookies->set(RememberTokens::TOKEN_COOKIE_KEY, $tokenValue, time() + RememberTokens::COOKIE_EXPIRY);
		$cookies->send();
	}

	public static function getCookies(Cookies $cookies) {
		return array($cookies->get(RememberTokens::USER_COOKIE_KEY),
					 $cookies->get(RememberTokens::TOKEN_COOKIE_KEY));
	}

	public static function deleteCookies(Cookies $cookies) {
		$cookies->delete(RememberTokens::USER_COOKIE_KEY);
		$cookies->delete(RememberTokens::TOKEN_COOKIE_KEY);
	}
}