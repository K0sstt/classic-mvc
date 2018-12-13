<?php

namespace app\models;

use app\core\Model;
use app\lib\Db;

class Main extends Model {

	public function getData() {
		$result = $this->db->row('SELECT id, name, mail, task, picture FROM user');
		return $result;
	}

	public function updateData($params) {
		$this->db->query('UPDATE user SET name = :name, mail = :mail, task = :task, picture = :picture WHERE id = :id', $params);
	}

	public $formats = [];
	public $actors = [];

	public function importData($films, $formats = [], $actors = []) {
		try {

			$this->db->beginTransaction();

			$this->formats = $this->db->row('SELECT * FROM formats');

			foreach($formats as $value) {
				if(!in_array_multi($value, $this->formats)) {
					$format['format'] = $value;
					$this->db->query('INSERT INTO formats (format)
						VALUES (:format)', $format);
					$this->formats[] = ['id' => $this->db->lastInsertId(), 'format' => $value];
				}
			}
			debug_light($this->formats);

			// debug($this->formats);

			// foreach($actors as $actor) {
			// 	$actor = explode(' ', $actor);
			// 	$actor['first_name'] = $actor[0];
			// 	$actor['last_name'] = $actor[1];
			// 	if(isset($actor[2])) {
			// 		$actor['first_name'] .= ' '.$actor[2];
			// 		$actor = array_slice($actor, 3);
			// 	} else {
			// 		$actor = array_slice($actor, 2);
			// 	}
			// 	// debug($actor);
			// 	$this->db->query('INSERT INTO actors (first_name, last_name)
			// 						VALUES (:first_name, :last_name)', $actor);
			// }
			
			
			// foreach($films as $film) {
			// 	$this->db->query('INSERT INTO films (title, year) 
			// 						VALUES (:title, :year)', $film);
			// }

			$this->db->commit();
		} catch(Exception $e) {
			$this->db->rollBack();
			echo $e->getMessage();
		}
	}
}

?>