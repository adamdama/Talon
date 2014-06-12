<?php
namespace Talon\Auth;

use \Phalcon\Mvc\User\Component,
	//\Phalcon\Db\RawValue,
	Talon\Models\Users\Users,
	Talon\Models\Users\RememberTokens,
	Talon\Models\Users\SuccessLogins,
	Talon\Models\Users\FailedLogins;

/**
 * Talon\Auth\Auth
 * Manages Authentication/Identity Management in Talon
 */
class Auth extends Component
{
	const AUTH_ID_SESSION_KEY = 'auth-identity';
	
	public function authenticate($credentials)
	{
		/** @var Users $user */
		// Check if the user exist
		$user = Users::findFirstByEmail($credentials['email']);
		if ($user == false) {
			$this->registerFailedLogin(0);
			throw new AuthException(AuthException::CREDENTIALS_FAILED);
		}

		// Check the password
		if (!$this->security->checkHash($credentials['password'], $user->password)) {
			$this->registerFailedLogin($user);
			throw new AuthException(AuthException::CREDENTIALS_FAILED);
		}

		// Check if the user is allowed
		$this->checkUserFlags($user);

		// Register the successful login
		$this->registerSuccessLogin($user);

		// Check if the remember me was selected
		if (isset($credentials['remember'])) {
			$this->createRememberTokens($user);
		}

		// authenticate the session
		$this->registerIdentity($user);
	}

	public function registerIdentity(Users $user) {
		$this->session->set(Auth::AUTH_ID_SESSION_KEY, array(
			'id' => $user->id,
			'name' => $user->name
		));
	}

	public function registerSuccessLogin(Users $user)
	{
		$successLogin = new SuccessLogins();
		$successLogin->usersId = $user->id;
		$successLogin->ipAddress = $this->request->getClientAddress();
		$successLogin->userAgent = $this->request->getUserAgent();

		if (!$successLogin->save()) {
			$messages = $successLogin->getMessages();
			throw new AuthException($messages[0]);
		}
	}

	public function registerFailedLogin($userId)
	{
		$failedLogin = new FailedLogins();
		$failedLogin->usersId = $userId;
		$failedLogin->ipAddress = $this->request->getClientAddress();
		$failedLogin->userAgent = $this->request->getUserAgent();

		if (!$failedLogin->save()) {
			$messages = $failedLogin->getMessages();
			throw new AuthException($messages[0]);
		}

//		$attempts = FailedLogins::count(array(
//			'ipAddress = ?0 AND created >= ?1',
//			'bind' => array(
//				ip2long($this->request->getClientAddress()),
//				new RawValue('now()')
//			)
//		));
//
//		switch ($attempts) {
//			case 1:
//			case 2:
//				// no delay
//				break;
//			case 3:
//			case 4:
//				sleep(2);
//				break;
//			default:
//				sleep(4);
//				break;
//		}
	}

	public function createRememberTokens(Users $user)
	{
		$userAgent = $this->request->getUserAgent();
		$token = md5($user->email . $user->password . $userAgent);

		$remember = new RememberTokens();
		$remember->usersId = $user->id;
		$remember->token = $token;
		$remember->userAgent = $userAgent;

		if ($remember->save() !== false) {
			$remember->setCookies($this->cookies, $user->id, $token);
		} else {
			foreach($remember->getMessages() as $m) {
				echo $m;
			}
			exit;
		}
	}

	public function hasRememberMe()
	{
		return $this->cookies->has(RememberTokens::USER_COOKIE_KEY);
	}

	public function loginWithRememberMe()
	{
		list($userId, $cookieToken) = RememberTokens::getCookies($this->cookies);


		$user = Users::findFirstById($userId);
		if ($user) {
			$token = md5($user->email.$user->password.$this->request->getUserAgent());

			if ($cookieToken == $token) {
				/** @var RememberTokens $remember */
				$remember = RememberTokens::findFirst(array(
					'usersId = ?0 AND token = ?1',
					'bind' => array(
						$user->id,
						$token
					)
				));


				if ($remember) {
					// Check if the cookie has not expired
					if ($remember->isExpired()) {
						// Check if the user was flagged
						$this->checkUserFlags($user);

						// Register identity
						$this->registerIdentity($user);

						return true;
					}
				} else {
					echo 1; exit;
				}
			}
		}

		RememberTokens::deleteCookies($this->cookies);

		return false;
	}

	public function checkUserFlags(Users $user)
	{
		if ($user->active !== 1) {
			throw new AuthException(AuthException::USER_NOT_ACTIVE);
		}

		if ($user->validated !== 1) {
			throw new AuthException(AuthException::USER_NOT_VALIDATED);
		}
	}

	public function getIdentity()
	{
		return $this->session->get(Auth::AUTH_ID_SESSION_KEY);
	}

	public function getName()
	{
		$identity = $this->session->get(Auth::AUTH_ID_SESSION_KEY);
		return $identity['name'];
	}

	public function unregisterIdentity()
	{
		RememberTokens::deleteCookies($this->cookies);

		$this->session->remove(Auth::AUTH_ID_SESSION_KEY);
	}

	public function authUserById($id)
	{
		/** @var Users $user */
		$user = Users::findFirstById($id);
		if ($user === false) {
			throw new AuthException(AuthException::USER_DOES_NOT_EXIST);
		}

		// check for valid user
		$this->checkUserFlags($user);

		$this->session->set(Auth::AUTH_ID_SESSION_KEY, array(
			'id' => $user->id,
			'name' => $user->name,
			//'profile' => $user->profile->name
		));
	}

	public function getUser()
	{
		$identity = $this->session->get(Auth::AUTH_ID_SESSION_KEY);
		if (isset($identity['id'])) {

			$user = Users::findFirstById($identity['id']);
			if ($user == false) {
				throw new AuthException(AuthException::USER_DOES_NOT_EXIST);
			}

			return $user;
		}

		return false;
	}
}