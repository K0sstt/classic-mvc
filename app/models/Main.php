<?php

namespace app\models;

use app\core\Model;

class Main extends Model {

	public function getData() {
		$result = $this->db->row('SELECT id, name, mail, task, picture FROM user');
		return $result;
	}

	public function updateData($params) {
		$this->db->query('UPDATE user SET name = :name, mail = :mail, task = :task, picture = :picture WHERE id = :id', $params);
	}

	public function addData($params) {
		$this->db->query('INSERT INTO user (name, mail, task, picture) VALUES (:name, :mail, :task, :picture)', $params);
	}
}

?>