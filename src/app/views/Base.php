<?php 

namespace app\views;


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
	
	
}





?>