<?php
namespace Talon\Controllers;

use \Phalcon\Tag,
	Talon\Forms\Session\SignUpForm,
	Talon\Forms\Session\LoginForm,
	Talon\Forms\Session\ForgotPasswordForm,
	Talon\Models\Users\Users,
	Talon\Models\Users\ResetPasswords,
	Talon\Auth\AuthException;

class SessionController extends ControllerBase
{
	/**
	 * Setup the page
	 */
	public function initialize()
	{
		parent::initialize();

		$this->view->cleanTemplateAfter();
	}

	public function indexAction()
    {

    }

	public function signupAction()
	{
		$form = new SignUpForm();

		if ($this->request->isPost()) {
			if ($form->isValid($this->request->getPost()) !== false) {
				$user = new Users();
				$user->name = $this->request->getPost('name');
				$user->email = $this->request->getPost('email');
				$user->setPassword($this->request->getPost('password'));

				if ($user->save() === false) {
					foreach($user->getMessages() as $message)
						$this->flashSession->error($message);
				} else {
					if(!$user->sendConfirmation()) {
						foreach($user->getMessages() as $message) {
							$this->flashSession->error($message);
						}
					} else {
						$this->flashSession->success('A confirmation email has been sent to your email address. You must confirm your email address before account access is granted.');
					}
					$this->flashSession->success('Thanks for sign-up.');
					return $this->redirect('session', 'login');
				}
			}
		}

		$this->view->setVar('form', $form);
	}

	/**
	 * Starts a session in the admin backend
	 */
	public function loginAction()
	{
		$form = new LoginForm();
		$redirect = false;

		try {
			if (!$this->request->isPost()) {
				if($this->auth->getIdentity()) {
					$redirect = true;
				} elseif ($this->auth->hasRememberMe()) {
					if($this->auth->loginWithRememberMe()) {
						$redirect = true;
					}
				}
			} else {
				if ($form->isValid($this->request->getPost()) == false) {
					foreach ($form->getMessages() as $message) {
						$this->flashSession->error($message);
					}
				} else {
					$this->auth->authenticate(array(
						'email' => $this->request->getPost('email'),
						'password' => $this->request->getPost('password'),
						'remember' => $this->request->getPost('remember')
					));

					$redirect = true;
				}
			}

			if($redirect) {
				return $this->redirect('users');
			}
		} catch (AuthException $e) {
			$error = $e->getMessage();
			if($error === AuthException::USER_NOT_VALIDATED) {
				$this->view->setVar('resendConfirmation', $this->request->getPost('email'));
			}

			$this->flashSession->error($error);
		}

		$this->view->setVar('form', $form);
	}

	/**
	 * Shows the forgot password form
	 */
	public function forgotPasswordAction()
	{
		$form = new ForgotPasswordForm();

		if ($this->request->isPost()) {
			if ($form->isValid($this->request->getPost()) === false) {
				foreach ($form->getMessages() as $message) {
					$this->flashSession->error($message);
				}
			} else {
				$user = Users::findFirstByEmail($this->request->getPost('email'));
				if (!$user) {
					$this->flashSession->error('There is no account associated with this email');
				} else {
					$resetPassword = new ResetPasswords();
					$resetPassword->usersId = $user->id;
					if ($resetPassword->save()) {
						$this->flashSession->success('Success! You have been sent an email with instructions on how to reset your password.');
						return $this->redirect('session', 'login');
					} else {
						foreach ($resetPassword->getMessages() as $message) {
							$this->flashSession->error($message);
						}
					}
				}
			}
		}

		$this->view->setVar('form', $form);
	}

	/**
	 * Closes the session
	 */
	public function logoutAction()
	{
		$this->auth->unregisterIdentity();

		return $this->redirect();
	}
}

