<?php

use Phalcon\Mvc\Controller;

/**
 * Class ControllerBase
 *
 * The one controller to rule them all
 */
class ControllerBase extends Controller
{
	/**
	 * Everything a growing controller needs
	 */
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

	/**
	 * Make sure that the request is what we were expecting
	 *
	 * @param string $method
	 * @param array $params
	 * @return bool
	 */
	protected function validateRequest($method = 'post', array $params = array()) {
		$valid = $method === 'post' ? $this->request->isPost() : !$this->request->isPost();

		return $valid;
	}
}
