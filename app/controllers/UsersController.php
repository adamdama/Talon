<?php
namespace Talon\Controllers;

use Talon\Models\Users;

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

		// set confirmation values
		$user->confirmEmail = $this->request->getPost('confirm_email');
		$user->confirmPassword = $this->request->getPost('confirm_password');

		if ($user->save($data) === false) {
			foreach ($user->getMessages() as $message) {
				$this->flashSession->error((string) $message);
			}
			$this->response->redirect('users/new');
		} else {
			$this->flashSession->success('Thanks for sign-up.');
			$this->response->redirect('users/registered');
		}

		return;
	}

}

