<?php

namespace app\controllers;

use app\core\Controller;

class MainController extends Controller {
	
	public function indexAction() {
		$result = $this->model->getData();
		// debug($result);
		// $vars = [
		// 	'data' => $result,
		// ];
		$this->view->render($result, false); 
	}

	

}

?>