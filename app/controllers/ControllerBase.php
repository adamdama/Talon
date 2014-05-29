<?php
namespace Talon\Controllers;

use \Phalcon\Mvc\Controller,
	\Phalcon\Tag;

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
		Tag::prependTitle('Talon | ');
	}

	/**
	 * Method for forwarding requests to different controller actions
	 *
	 * @param $uri
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
	 * @return bool
	 */
	protected function validateRequest($method = 'post') {
		$valid = $method === 'post' ? $this->request->isPost() : !$this->request->isPost();

		return $valid;
	}
}
