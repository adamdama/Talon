<?php
namespace Talon\Controllers;

use Talon\Forms\Session\SignUpForm,
	Talon\Forms\Session\LoginForm,
	Talon\Forms\Session\ForgotPasswordForm,
	Talon\Models\Users,
	Talon\Models\Users\ResetPasswords,
	Talon\Auth\Auth,
	Talon\Auth\AuthException;

class SessionController extends ControllerBase
{
	/**
	 * @var Auth $auth
	 */

	public function indexAction()
    {

    }

	public function signupAction()
	{
		$form = new SignUpForm();

		if ($this->request->isPost()) {
			if ($form->isValid($this->request->getPost()) !== false) {
				$user = new Users();
				// filter post data for desired values
				$data = array(
					'name' => $this->request->getPost('name'),
					'email' => $this->request->getPost('email'),
					'password' => $this->request->getPost('password')
				);

				if ($user->save($data) === false) {
					foreach($user->getMessages() as $message)
						$this->flashSession->error($message);
				} else {
					$this->flashSession->success('Thanks for sign-up.');
					return $this->response->redirect('session/login');
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

		try {
			if (!$this->request->isPost()) {
				if ($this->auth->hasRememberMe()) {
					if($this->auth->loginWithRememberMe()) {
						return $this->response->redirect('users');
					}
				}
			} else {
				if ($form->isValid($this->request->getPost()) == false) {
					foreach ($form->getMessages() as $message) {
						$this->flash->error($message);
					}
				} else {
					$this->auth->authenticate(array(
						'email' => $this->request->getPost('email'),
						'password' => $this->request->getPost('password'),
						'remember' => $this->request->getPost('remember')
					));

					return $this->response->redirect('users');
				}
			}
		} catch (AuthException $e) {
			$this->flash->error($e->getMessage());
		}

		$this->view->setVar('form', $form);
	}

	/**
	 * Closes the session
	 */
	public function logoutAction()
	{
		$this->auth->unregisterIdentity();

		return $this->response->redirect('index');
	}
}

