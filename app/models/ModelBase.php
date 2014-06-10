<?php
namespace Talon\Models;

use \Phalcon\Mvc\Model;

/**
 * Class ModelBase
 */
class ModelBase extends Model {

	public $id;

//	/**
//	 *
//	 *
//	 * @param $id
//	 * @return bool|Users
//	 */
//	public static function findFirstById($id) {
//		if(!is_numeric($id))
//			return false;
//
//		return self::findFirst(array("id='{$id}'"));
//	}
} 