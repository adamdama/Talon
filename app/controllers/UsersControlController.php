<?php
namespace Talon\Controllers;

use Talon\Auth\AuthException,
	Talon\Models\Users\ResetPasswords,
	Talon\Models\Users\EmailConfirmations;
use Talon\Models\Users\Users;

/**
 * Class IndexController
 */
class UsersControlController extends ControllerBase
{
	/**
	 * Home sweet home
	 */
	public function indexAction()
    {

    }

	/**
	 * Confirms an e-mail, if the user must change thier password then changes it
	 */
	public function confirmEmailAction()
	{
		$code = $this->dispatcher->getParam('code');
		/** @var \Talon\Models\Users\EmailConfirmations $confirmation */
		$confirmation = EmailConfirmations::findFirstByCode($code);

		if (!$confirmation) {
			return $this->dispatcher->forward(array(
				'controller' => 'index',
				'action' => 'index'
			));
		}

		if ($confirmation->confirmed !== 0) {
			return $this->dispatcher->forward(array(
				'controller' => 'session',
				'action' => 'login'
			));
		}

		$confirmation->confirmed = 1;
		$confirmation->user->active = 1;
		$confirmation->user->validated = 1;

		/**
		 * Change the confirmation to 'confirmed' and update the user to 'active'
		 */
		if (!$confirmation->save()) {
			foreach ($confirmation->getMessages() as $message) {
				$this->flashSession->error($message);
			}

			return $this->dispatcher->forward(array(
				'controller' => 'index',
				'action' => 'index'
			));
		}

		$this->flashSession->success('The email was successfully confirmed. Please login.');

		return $this->redirect('session', 'login');
	}

	public function resetPasswordAction()
	{
		$code = $this->dispatcher->getParam('code');

		/** @var \Talon\Models\Users\ResetPasswords $resetPassword */
		$resetPassword = ResetPasswords::findFirstByCode($code);
		// if the record cannot be found by code then redirect to home
		// probably a naughty person trying to hack an account
		if (!$resetPassword) {
			$this->redirect();
			return;
		}

		// if we are receiving post then we just need to forward to the change password form
		if(!$this->request->isPost()) {
			// if the record is not still active then redirect to login page
			// the user probably got here by mistake or from an old link
			if ($resetPassword->reset !== 0) {
				$this->redirect('session', 'login');
				return;
			}

			$resetPassword->reset = 1;

			/**
			 * Change the confirmation to 'reset'
			 */
			if (!$resetPassword->save()) {
				foreach ($resetPassword->getMessages() as $message) {
					$this->flashSession->error($message);
				}

				$this->redirect();
				return;
			}
		}

		/**
		 * Identify the user in the application
		 */
		try {
			$this->auth->authUserById($resetPassword->usersId);
		} catch(AuthException $e) {
			$this->flashSession->error($e->getMessage());
			$this->redirect('session', 'login');
		}

		$this->forward('users/changePassword');
	}

	public function resendConfirmationAction() {
		$email = $this->dispatcher->getParam('email');
		/** @var \Talon\Models\Users\Users $user */
		$user = Users::findFirstByEmail($email);

		if($user->validated === 0) {
			if(!$user->sendConfirmation()) {
				foreach($user->getMessages() as $message) {
					$this->flashSession->error($message);
				}
			} else {
				$this->flashSession->success('A confirmation email has been sent to your email address. You must confirm your email address before account access is granted.');
			}
		} else {
			$this->flashSession->error('The email was not sent.');
		}

		return $this->redirect('session', 'login');
	}
}