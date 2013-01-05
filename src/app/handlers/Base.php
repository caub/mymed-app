<?php 

namespace app\handlers;


class Base {

	
	function read( $data, $other = array() ) {
	}
	
	function create( $data ) {
	}
	
	function delete( $data ) {
	}
	
	function update( $data ) {
	}
	
	function errorAuthentication ( $data){
		//$this->read($data, array('notification'=>'please log in'));
		header('Location:/'.APP_NAME.'/login');
		
	}
	function error ( $data){
		$this->read($data, array('notification'=>'sorry but not authorized'));
	}
	
	
	/* load html templates with handlers variables */
	function loadTemplates($templatesName, $data = array(), $other = array()){
	
		//include __DIR__ . '/templates/header.php';
		include __DIR__ . '/../templates/header.php';
		foreach ($templatesName as $templateName){
			include __DIR__ . '/../templates/' .$templateName. '.php';
		}
		include __DIR__ . '/../templates/footer.php';
		exit();
	}
	
	
}





?>