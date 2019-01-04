<?php 

namespace app\controllers;

use app\core\Controller;

class ImportController extends Controller {

	public $films = [];
	public $formats = [];
	public $actors = [];

	public function indexAction() {
		$file = fopen($_FILES['films']['tmp_name'], "r");
		
		$i = 0;
		while(!feof($file)) {
			$text = fgets($file);
			$films = explode(':', $text);

			switch($films[0]) {
				case 'Title':
					$this->films[$i]['title'] = trim($films[1]);
					if(isset($films[2])) $this->films[$i]['title'] .= ':'.$films[2];
					break;
				case 'Release Year':
					$this->films[$i]['release'] = trim($films[1]);
					break;
				case 'Format':
					$this->films[$i]['format'] = trim($films[1]);
					if(!in_array($this->films[$i]['format'], $this->formats)) {
						$this->formats[] = $this->films[$i]['format'];
					}
					break;
				case 'Stars':
					$this->films[$i]['actors'] = explode(', ', trim($films[1]));
					foreach($this->films[$i]['actors'] as $actor) {
						if(!in_array($actor, $this->actors)) {
							$this->actors[] = $actor;
						}
					}
					break;
				default:
					++$i;
					continue 2;
			}
		
		}
		fclose($file);

		$this->model->importData($this->films, $this->formats, $this->actors);

		$this->view->render($this->films, false);
	}
}