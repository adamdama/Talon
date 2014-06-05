<?php
namespace Talon\Controllers;

use Talon\Forms\Session\SignUpForm,
	Talon\Models\Users;

class SessionController extends ControllerBase
{

    public function indexAction()
    {

    }

	public function signupAction()
	{
		$form = new SignUpForm();

		if ($this->request->isPost()) {

			$valid = $form->isValid($this->request->getPost());

			if ($valid !== false) {
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
					$this->response->redirect('session/registered');
				}
			} else {
				$this->response->redirect('session/signup');
			}
		}

		$this->view->setVar('form', $form);
	}

	public function loginAction() {
		// make sure request is post and the token is valid
		if(!$this->validateRequest(array('method' => 'post', 'token' => 'token'))) {
			$this->response->redirect('users/new');
			return;
		}

	}

	public function registeredAction() {

	}
}

