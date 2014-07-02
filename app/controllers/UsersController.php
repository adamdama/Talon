<?php
namespace Talon\Controllers;

use Phalcon\Tag;
use Talon\Forms\Users\ChangePasswordForm,
	Talon\Models\Users\Users;
use Talon\Models\Users\PasswordChanges;
use Talon\Models\Users\ResetPasswords;

/**
 * Class UsersController
 *
 *
 */
class UsersController extends ControllerBase
{
	/**
	 * Setup the page
	 */
	public function initialize()
	{
		//$this->view->setTemplateAfter('main');
		//Tag::setTitle('Sign Up/Sign In');
		parent::initialize();
	}

	/**
	 * Users home page
	 */
	public function indexAction() {
		var_dump($this->auth->getIdentity());
    }

	/**
	 * Create a new user
	 *
	 * @return \Phalcon\Http\ResponseInterface
	 */
	public function newAction()
	{
		// make sure request is post and the token is valid
		if(!$this->validateRequest(array('method' => 'post', 'token' => 'token'))) {
			$this->response->redirect('users/new');
			return;
		}

		$user = new Users();

		// filter post data for desired values
		$data = array(
			'name' => $this->request->getPost('name'),
			'email' => $this->request->getPost('email'),
			'password' => $this->request->getPost('password')
		);

		if ($user->save($data) === false) {
			foreach ($user->getMessages() as $message) {
				$this->flashSession->error((string) $message);
			}

			$this->response->redirect('users/new');
		}
	}

	public function changePasswordAction() {
		// get the user resetting password and then log them out so they can't navigate
		// to other protected parts of the site
		// rewrite comment,idea has changed
		/** @var \Talon\Models\Users\Users $user */
		$user = $this->auth->getUser();

		if(!$user) {
			$this->flashSession->error(Users::USER_DOES_NOT_EXIST);
			$this->response->redirect('session/login');
		}

		$form = new ChangePasswordForm();

		$code = $this->dispatcher->getParam('code');
		if($code) {
			$resetPasswords = ResetPasswords::findFirstByCode($code);

			if($resetPasswords->reset === 1) {
				$this->auth->unregisterIdentity();
			}
		}

		if ($this->request->isPost()) {
			if ($form->isValid($this->request->getPost()) !== false) {
				$passwordChange = new PasswordChanges();
				$passwordChange->usersId = $user->id;
				$passwordChange->ipAddress = $this->request->getClientAddress();
				$passwordChange->userAgent = $this->request->getUserAgent();

				if (!$passwordChange->save()) {
					$this->flashSession->error($passwordChange->getMessages());
				} else {
					$user->setPassword($this->request->getPost('password'));

					if ($user->save() === false) {
						// log user back in so they can get to the form
						// in case they were resetting password and were logged out
						$this->auth->authUserById($user->id);

						foreach($user->getMessages() as $message)
							$this->flashSession->error($message);
					} else {
						$this->flashSession->success('Password changed successfully.');
						return $this->response->redirect('session/login');
					}
				}
			}
		}

		Tag::resetInput();

		$this->view->setVar('form', $form);
	}

}

