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
			return $this->response->redirect('users/new');
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

			return $this->response->redirect('users/new');
		}
	}

}

