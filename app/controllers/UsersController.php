<?php


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
	public function indexAction()
    {

    }

	/**
	 * User registeation
	 */
	public function newAction()
	{

	}

	/**
	 * Create a new user
	 *
	 * @return \Phalcon\Http\ResponseInterface
	 */
	public function createAction()
	{
		// make sure request is post
		if(!$this->validateRequest()) {
			return $this->response->redirect('users/new');
		}

		$user = new Users();

		if ($user->save($this->request->getPost(), array('name', 'email', 'password')) === false) {
			foreach ($user->getMessages() as $message) {
				$this->flashSession->error((string) $message);
			}
			return $this->forward('users/new');
		} else {
			$this->flashSession->success('Thanks for sign-up, please log-in to start generating invoices');
			return $this->forward('users/registered');
		}
	}

	/**
	 * User registered
	 */
	public function registeredAction() {

	}

}

