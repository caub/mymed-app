<?php

namespace app\accesscontrol;




class Ac {
	
	protected $authorizedMethods = null;
	protected $fallback = null;

	/*
	 * targetted view
 	*/
	protected $target = null;
	
	public function __construct( $target, $authorizedMethods, $fallback){
		$this->target = $target;
		$this->authorizedMethods = $authorizedMethods;
		$this->fallback = $fallback;
	}
	
	public function __call( $method, $arguments ){
		
		if ( is_callable( array($this->target, $method)) && in_array($method, $this->authorizedMethods) ){
			return call_user_func_array(
				array( $this->target, $method ),
				$arguments
			);
		} else {
			return call_user_func_array(
				array( $this->target, $this->fallback ),
				$arguments
			);
		}
	}
	
}
