<?php 

namespace app\handlers;

use lib\database\BackendRequest;
use app\views\Utils;


class Logout extends Base {


	function read( $data, $other = array() ) {
		
		$request = new BackendRequest();
		$responsejSon = $request->delete("v2/SessionRequestHandler", $data);
		session_unset($_SESSION['user']);
		session_destroy();
		
		
		debug("efef");
				
		$this->loadTemplates( array('login', 'register', 'about'), $data);
		
	}
	
}





?>