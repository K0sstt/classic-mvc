<?php

namespace app\models;

use app\core\Model;
use app\lib\Db;

class Main extends Model {

	public function getData() {
		$result = $this->db->row('SELECT films.id, films.title, formats.format, GROUP_CONCAT(actors.first_name, " ", actors.last_name) AS actors
									FROM films
									INNER JOIN formats ON films.format_id = formats.id
									INNER JOIN films_actors ON films.id = films_actors.film_id
									INNER JOIN actors ON actors.id = films_actors.actor_id
									GROUP BY films.id');
		return $result;
	}

	public function updateData($params) {
		$this->db->query('UPDATE user SET name = :name, mail = :mail, task = :task, picture = :picture WHERE id = :id', $params);
	}



	
}

?>