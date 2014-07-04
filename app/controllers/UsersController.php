<?php
namespace Talon\Controllers;

use Phalcon\Tag;
use Talon\Forms\Users\ChangePasswordForm,
	Talon\Forms\Users\UsersForm,
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
	const USER_DOES_NOT_EXIST = 'User does not exist';
	const CONFIRMATION_EMAIL_SENT = 'A confirmation email has been sent to the users email address.';

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
		$users = Users::find();

		$this->view->setVar('users', $users);
    }

	/**
	 * Create a new user
	 *
	 * @return \Phalcon\Http\ResponseInterface
	 */
	public function newAction() {
		$form = new UsersForm(null);

		if ($this->request->isPost()) {
			if ($form->isValid($this->request->getPost()) !== false) {
				$user = new Users();
				$user->name = $this->request->getPost('name');
				$user->email = $this->request->getPost('email');
				$user->setPassword($this->request->getPost('password'));
				$user->validated = $this->request->getPost('validated');
				$user->active = $this->request->getPost('active');

				if ($user->save() === false) {
					foreach($user->getMessages() as $message)
						$this->flashSession->error($message);
				} else {
					if($user->validated === 0) {
						if(!$user->sendConfirmation()) {
							foreach($user->getMessages() as $message) {
								$this->flashSession->error($message);
							}
						} else {
							$this->flashSession->success(self::CONFIRMATION_EMAIL_SENT);
						}
					}

					$this->flashSession->success('User created.');
					$this->forward('users/edit');
					return;
				}
			}
		}

		$this->view->setVar('form', $form);
	}

	/**
	 * Edit a user
	 *
	 * @param $id
	 * @return \Phalcon\Http\ResponseInterface
	 */
	public function editAction($id) {
		if(!$id) {
			return $this->response->redirect('users');
		}

		/** @var \Talon\Models\Users\Users $user */
		$user = Users::findFirstById($id);

		if(!$user) {
			$this->flashSession->error(UsersController::USER_DOES_NOT_EXIST);
			$this->response->redirect('users/index');
		}

		$form = new UsersForm($user, array('edit' => true));

		if ($this->request->isPost()) {
			if ($form->isValid($this->request->getPost()) !== false) {
				$user->name = $this->request->getPost('name');
				$user->email = $this->request->getPost('email');
				$user->setPassword($this->request->getPost('password'));
				$user->validated = $this->request->getPost('validated');
				$user->active = $this->request->getPost('active');

				if ($user->save() === false) {
					foreach($user->getMessages() as $message)
						$this->flashSession->error($message);
				} else {
					$this->flashSession->success('User was updated successfully');
				}
			}
		}

		Tag::resetInput();

		$this->view->setVar('user', $user);
		$this->view->setVar('form', $form);
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

	public function deleteAction($id, $sure =  false) {
		if($id) {
			$user = Users::findFirstById($id);
			if($this->auth->getUser()->id === (int) $id) {
				$this->flashSession->error('You cannot delete yourself.');

			} elseif($sure === 'yes') {
				/** @var \Talon\Models\Users\Users $user */
				$userName = $user->name;
				$user->delete();
				$this->flashSession->notice($userName.' has been deleted.');

				return $this->response->redirect('users');
			} else {
				$this->flashSession->notice('The user was not deleted.');
			}

			return $this->response->redirect('users/edit/'.$id);
		}

		return $this->response->redirect('users');
	}

}

