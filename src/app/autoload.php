<?php



spl_autoload_register(function($className){
	if (strpos($className, 'app') === 0 ) {
		require_once __DIR__.'/../'.str_replace('\\', '/', $className).'.php';
	} else {
		return false;
	}
});

?>