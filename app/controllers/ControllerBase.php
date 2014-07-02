<?php
namespace Talon\Controllers;

use \Phalcon\Exception,
	\Phalcon\Mvc\Controller,
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
		if(!is_string($uri))
			throw new Exception('forward expects uri format string');

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
	 * @param array $checks
	 * @return bool
	 */
	protected function validateRequest($checks = array()) {
		$valid = true;

		foreach($checks as $key => $value) {
			if($key === 'method' && $value === 'post') {
				$valid = $this->request->isPost();

				if(!$valid)
					$this->flashSession->error("Only POST requests can be meade to this URL");
			} elseif($key === 'token') {
				$valid = $this->security->checkToken();

				if(!$valid)
					$this->flashSession->error("Incorrect security token");
			}

			if(!$valid)
				break;
		}

		return $valid;
	}
}
