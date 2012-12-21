<?php 

namespace app\views;

use lib\database\BackendRequest;
use app\views\Utils;


class Logout extends Base {


	function read( $data, $other = array() ) {
		
		$request = new BackendRequest();
		$responsejSon = $request->delete("v2/SessionRequestHandler", $data);
		
		session_destroy();
		unset($_SESSION['user']);
				
		Utils::load( array('login', 'register', 'about'), $data);
		
	}
	
}





?>