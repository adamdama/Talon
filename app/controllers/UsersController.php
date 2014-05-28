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
		$data = $this->request->getPost();

		$user->created = new Phalcon\Db\RawValue('now()');
		$user->confirmEmail = $data['confirm_email'];
		$user->confirmPassword = $data['confirm_password'];

		if ($user->save($data, array('name', 'email', 'password', 'created')) === false) {
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

