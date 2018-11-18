<?php

namespace app\controllers;

use app\core\Controller;

class MainController extends Controller {

	public $picture = 'no-image';
	
	public function indexAction() {
		$result = $this->model->getData();
		$vars = [
			'data' => $result,
		];
		$this->view->render('main page', $vars); 
	}

	public function addDataAction() {
		$types = array('image/gif', 'image/png', 'image/jpeg',);


		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			if(!isset($_FILES['picture']['name']))
				if(in_array($_FILES['picture']['type'], $types))
					$this->picture = $this->resize($_FILES);
				
			// else exit();


			$params = [
				'name' => $_POST['name'],
				'mail' => $_POST['mail'],
				'task' => $_POST['task'],
				'picture' => $this->picture,
			];

			$this->model->addData($params);
		}
	}

	public function updateDataAction() {
		$types = array('image/gif', 'image/png', 'image/jpeg',);

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			if(!isset($_FILES['picture']['name']))
				$picture = 'no-image';
			elseif(in_array($_FILES['picture']['type'], $types))
				$picture = $this->resize($_FILES);
			else exit();

			// if(!@copy($_FILES['picture']['tmp_name'], 'public/img/'.$_FILES['picture']['name']))
			// 	echo "bad";
			// echo "good";

			$params = [
				'id' => $_POST['id'],
				'name' => $_POST['name'],
				'mail' => $_POST['mail'],
				'task' => $_POST['task'],
				'picture' => $picture,
			];

			$this->model->updateData($params);
		}
	}

	private function resize($file) {
		$wsize = 320;
		$hsize = 240;

		if($file['picture']['type'] == 'image/jpeg')
			$picture = imagecreatefromjpeg($file['picture']['tmp_name']);
		elseif($file['picture']['type'] == 'image/png') 
			$picture = imagecreatefrompng($file['picture']['tmp_name']);
		elseif($file['picture']['type'] == 'image/gif') 
			$picture = imagecreatefromgif($file['picture']['tmp_name']);
		else
			return false;

		$w_picture = imagesx($picture);
		$h_picture = imagesy($picture);

		$ratio = $w_picture/$wsize;
		$w_dest = round($w_picture/$ratio);
		$h_dest = round($h_picture/$ratio);

		$dest = imagecreatetruecolor($wsize, $hsize);
		imagecopyresampled($dest, $picture, 0, 0, 0, 0, $w_dest, $h_dest, $w_picture, $h_picture);

		if($file['picture']['type'] == 'image/jpeg')
			imagejpeg($picture, 'public/img/s'.$file['picture']['name']);
		elseif ($file['picture']['type'] == 'image/png') 
			imagepng($picture, 'public/img/s'.$file['picture']['name']);
		elseif ($file['picture']['type'] == 'image/gif') 
			imagegif($picture, 'public/img/s'.$file['picture']['name']);

		imagedestroy($dest);
		imagedestroy($picture);

		return 's'.$file['picture']['name'];
	}

}

?>