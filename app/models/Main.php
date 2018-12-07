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

	public function importData($films, $formats = [], $actors = []) {
		try {
			$this->db->beginTransaction();

			foreach($formats as $value) {
				$format['format'] = $value;
				$this->db->query('INSERT INTO formats (format)
									VALUES (:format)', $format);
			}

			foreach($actors as $actor) {
				$actor = explode(' ', $actor);
				$actor['first_name'] = $actor[0];
				$actor['last_name'] = $actor[1];
				if(isset($actor[2])) {
					$actor['first_name'] .= ' '.$actor[2];
					$actor = array_slice($actor, 3);
				} else {
					$actor = array_slice($actor, 2);
				}
				$this->db-query('INSERT INTO actors (first_name, last_name)
									VALUES (:first_name, :last_name)', $actor);
			}
			
			
			foreach($films as $film) {
				$this->db->query('INSERT INTO formats (format) 
									VALUES (:format)', $film);
				$this->db->query('INSERT INTO films (title, year, format_id) 
									VALUES (:title, :year, :'.$this->db->lastInsertId().')', $film);
			}

			$this->db->commit();
		} catch(Exception $e) {
			$this->db->rollBack();
			echo $e->getMessage();
		}
	}
}

?>