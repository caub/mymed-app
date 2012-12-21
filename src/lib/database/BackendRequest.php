<?php

namespace lib\database;

/*
 * where the database and a small Java server lies
 */


class BackendRequest extends CoreRequest{

	
	function __construct() {
		parent::__construct(BACKEND_URL);
	}

	
	function create($ressource, $data){
		$data = $this->setServiceParams($data, __METHOD__);
		return $this->sendPost($ressource, $data);
	}
	function read($ressource, $data){
		$data = $this->setServiceParams($data, __METHOD__);
		return $this->sendGet($ressource, $data);
	}
	function update($ressource, $data){
		$data = $this->setServiceParams($data, __METHOD__);
		return $this->sendPost($ressource, $data);
	}
	function delete($ressource, $data){
		$data = $this->setServiceParams($data, __METHOD__);
		return $this->sendGet($ressource, $data);
	}

	
	
	function setServiceParams($data, $methodPath) { //Service specific params
		if(isset($_SESSION['accessToken']) && !isset($data['accessToken'])) {
			$data['accessToken'] = $_SESSION['accessToken'];
		}
		$data['application'] = APP_NAME;

		$parts = explode('::', $methodPath);
		$method= $parts[1];
		$codes = array(
			'create' => 0,
			'read'   => 1,
			'update' => 2,
			'delete' => 3
		);
		$data['code'] = $codes[$method];
		return $data;
	}
	
	

	
}
?>
