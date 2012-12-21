<?php 

namespace app\views;


class Utils {
	
	static function load($templatesName, $data = array(), $other = array()){ 

		//include __DIR__ . '/templates/header.php';
		include 'templates/header.php';
		foreach ($templatesName as $templateName){
			include 'templates/' .$templateName. '.php';
		}
		include 'templates/footer.php';
		exit();
	}
	
}


?>