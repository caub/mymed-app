<?php

namespace app\routing;


class Router {

	
	function __construct( $config ) {
		$this->config = $config;
	}
	
	function route($uri, $accessControl) {
			
		preg_match( $this->config['routeExpression'], $uri, $matches);
		
		//debug_r($matches);

		$method = $this->config['defaultMethod'];
		
		if (isset($_REQUEST['method'])){ //method comes from query string
			$method = $_REQUEST['method'];
			unset($_REQUEST['method']);
		}
		
		$viewName = isset($matches['page']) ? $matches['page'] : $this->config['defaultPage'];
		
		if (isset($matches['id'])){ //puts id in the query string, if the router detected one
			$_REQUEST['id'] = $matches['id'];
		}
		
		$authorizedMethods = array_key_exists($viewName, $accessControl)? $accessControl[$viewName]:$accessControl['default'];
		
		$viewName = 'app\\handlers\\' . ucwords($viewName);
		$view = new $viewName;

		$ac = new \app\accesscontrol\Ac($view, $authorizedMethods, $accessControl['fallback']);

		//call it
		try{
			return call_user_func(
				array( $ac, $method ),
				$_REQUEST
			);
			exit();
		} catch (Exception $e){
			debug($e->getMessage());
		}

	}
	
	
}