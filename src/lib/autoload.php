<?php



spl_autoload_register(function($className){
	if (strpos($className, 'lib') === 0) {/*will be moved in all apps classloader */
		require_once __DIR__.'/../'.str_replace('\\', '/', $className).'.php';
	} else {
		return false;
	}
});

?>