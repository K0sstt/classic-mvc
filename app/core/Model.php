<?php

namespace app\core;

use app\lib\Db;
use PDO;

abstract class Model {

	public $db;

	public function __construct() {
		$this->db = new Db;
	}


}

?>