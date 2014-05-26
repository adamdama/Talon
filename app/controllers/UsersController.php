<?php



class UsersController extends ControllerBase
{

	public function initialize()
	{
		//$this->view->setTemplateAfter('main');
		//Tag::setTitle('Sign Up/Sign In');
		parent::initialize();
	}

    public function indexAction()
    {

    }

	public function newAction()
	{

	}

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

	public function registeredAction() {

	}

}

