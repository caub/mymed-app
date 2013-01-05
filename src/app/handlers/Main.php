<?php 

namespace app\handlers;

use lib\database\BackendRequest;
use app\views\Utils;


class Main extends Base {

	
	function read( $data, $other = array() ) {

		$request = new BackendRequest;
		$responsejSon = $request->read("v2/ProfileRequestHandler", array('id' => $_SESSION['user']->id) );
		$responseObject = json_decode($responsejSon);
		//debug_r($responseObject);
		if ($responseObject->status==200){
			$data = (array) $responseObject->dataObject->user;
		}

		$this->loadTemplates( array('map', 'profile'), $data, $other);
		
	}
	
}





?>