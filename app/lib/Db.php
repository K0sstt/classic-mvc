<?php

namespace app\lib;

use PDO;

class Db {

	protected $db;
	
	public function __construct() {
		$config = require 'app/config/db.php';
		try {
			$this->db = new PDO('mysql:host='.$config['host'].';dbname='.$config['dbname'], $config['user'], $config['password']);
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}

	public function query($sql, $params = []) {
		$stmt = $this->db->prepare($sql, $params);
		if(!empty($params)) {
			foreach($params as $key => $value) {
				$stmt->bindValue(':'.$key, $value);
			}
		}
		$stmt->execute();
		return $stmt;
	}

	public function row($sql, $params = []) {
		$result = $this->query($sql, $params);
		return $result->fetchAll(PDO::FETCH_ASSOC);
	}

	public function column($sql, $params = []) {
		$result = $this->query($sql, $params);
		return $result->fetchColumn();
	}

}

?>