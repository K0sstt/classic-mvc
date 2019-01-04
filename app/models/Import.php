<?php

namespace app\models;

use app\core\Model;
use app\lib\Db;

class Import extends Model {

	public $formats = [];
	public $actors = [];
	public $actor_name = false;
	public $films = [];
	public $film = [];
	public $format_id;
	public $film_id;
	
	public function importData($films, $formats = [], $actors = []) {
		try {

			$this->db->beginTransaction();

			// FORMATS
			$this->formats = $this->db->row('SELECT * FROM formats');

			foreach($formats as $item) {

				if(empty($this->formats)) {
					$format['format'] = $item;
					$this->db->query('INSERT INTO formats (format)
										VALUES (:format)', $format);
					$this->formats[] = ['id' => $this->db->lastInsertId(), 
										'format' => $item];

				} elseif(!in_array_multi($item, $this->formats)) {
					$format['format'] = $item;
					$this->db->query('INSERT INTO formats (format)
										VALUES (:format)', $format);
					$this->formats[] = ['id' => $this->db->lastInsertId(), 
										'format' => $item];
				}
			}

			// ACTORS
			$this->actors = $this->db->row('SELECT * FROM actors');

			foreach($actors as $actor) {
				$actor = explode(' ', $actor);
				$actor['first_name'] = $actor[0];

				if(isset($actor[2])) {
					$actor['first_name'] .= ' '.$actor[1];
					$actor['last_name'] = $actor[2];
					$actor = array_slice($actor, 3);

				} else {
					$actor['last_name'] = $actor[1];
					$actor = array_slice($actor, 2);
				}

				if(empty($this->actors)) {
					$this->db->query('INSERT INTO actors (first_name, last_name)
										VALUES (:first_name, :last_name)', $actor);

					$this->actors[] = ['id' => $this->db->lastInsertId(),
										'first_name' => $actor['first_name'],
										'last_name' => $actor['last_name']];
					
				} else {
					foreach($this->actors as $row) {
						if($actor['first_name'] == $row['first_name'] && $actor['last_name'] == $row['last_name']) {
							$this->actor_name = true;
						} else {
							continue;
						}
					}

					if(!$this->actor_name) {
						$this->db->query('INSERT INTO actors (first_name, last_name)
											VALUES (:first_name, :last_name)', $actor);

						$this->actors[] = ['id' => $this->db->lastInsertId(),
											'first_name' => $actor['first_name'],
											'last_name' => $actor['last_name']];
					}
				}
			}
			
			// FILMS
			$this->films = $this->db->row('SELECT * FROM films');

			foreach($films as $film) {
				if(empty($this->films)) {
					
					foreach($this->formats as $format) {
						if($film['format'] == $format['format']) $this->format_id = $format['id'];
					}

					$this->film = ['title' => $film['title'],
									'year' => $film['release'],
									'format_id' => $this->format_id]; 
					
					$this->db->query('INSERT INTO films (title, year, format_id)
										VALUES (:title, :year, :format_id)', $this->film);
					

					$this->films[] = ['id' => $this->db->lastInsertId(),
										'title' => $this->film['title'],
										'year' => $this->film['year'],
										'format_id' => $this->film['format_id']];

					$this->film_id = $this->db->lastInsertId();

					foreach($this->actors as $actor) {
						if(in_array($actor['first_name'].' '.$actor['last_name'], $film['actors'])) {
							$films_actors = ['film_id' => $this->film_id,
												'actor_id' => $actor['id']];
							$this->db->query('INSERT INTO films_actors (film_id, actor_id)
								VALUES (:film_id, :actor_id)', $films_actors);
						}
					}
				
				} elseif(!in_array_multi($film['title'], $this->films)) {

					foreach($this->formats as $format) {
						if($film['format'] == $format['format']) $this->format_id = $format['id'];
					}

					$this->film = ['title' => $film['title'],
									'year' => $film['release'],
									'format_id' => $this->format_id]; 
					
					$this->db->query('INSERT INTO films (title, year, format_id)
										VALUES (:title, :year, :format_id)', $this->film);

					$this->films[] = ['id' => $this->db->lastInsertId(),
										'title' => $this->film['title'],
										'year' => $this->film['year'],
										'format_id' => $this->film['format_id']];

					$this->film_id = $this->db->lastInsertId();

					foreach($this->actors as $actor) {
						if(in_array($actor['first_name'].' '.$actor['last_name'], $film['actors'])) {
							$films_actors = ['film_id' => $this->film_id,
												'actor_id' => $actor['id']];
							$this->db->query('INSERT INTO films_actors (film_id, actor_id)
								VALUES (:film_id, :actor_id)', $films_actors);
						}
					}
				}
			}


			$this->db->commit();
		} catch(Exception $e) {
			$this->db->rollBack();
			echo $e->getMessage();
		}
	}
}