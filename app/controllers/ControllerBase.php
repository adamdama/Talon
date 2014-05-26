<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
	protected function initialize()
	{
		//Phalcon\Tag::prependTitle('INVO | ');
	}

	/**
	 * Method for forwarding requests to different controller actions
	 *
	 * @param $uri
	 * @return forwarde
	 */
	protected function forward($uri){
		$uriParts = explode('/', $uri);
		$this->dispatcher->forward(
			array(
				'controller' => $uriParts[0],
				'action' => $uriParts[1]
			)
		);
	}

	protected function validateRequest($method = 'post', array $params = array()) {
		$valid = $method === 'post' ? $this->request->isPost() : !$this->request->isPost();

		return $valid;
	}
}
