<?php

namespace app\controllers;

use app\core\Controller;

class AccountController extends Controller {
	
	public function singupAction() {
		$this->view->render('Sing Up');
	}

}

?>