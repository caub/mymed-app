
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width" />
	<title><?= APP_NAME ?></title> 
	<link href="/<?= APP_NAME ?>/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="/<?= APP_NAME ?>/css/app.css" rel="stylesheet" />

	
	<script src="/<?= APP_NAME ?>/javascript/jquery-1.8.2.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="/<?= APP_NAME ?>/javascript/bootstrap.min.js"></script>
	
	<script src="/<?= APP_NAME ?>/javascript/map.js"></script>
	<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
	
	<script type="text/javascript">
		<?php $accessToken =& $_SESSION['accessToken']; ?>
		var accessToken = '<?= $accessToken ?>';
		<?php $name =& $_SESSION['user']->name; ?>
		var user_name = '<?= $name ?>';
		var app_name = '<?= APP_NAME ?>';
		var backend = '<?= BACKEND_URL ?>';
	</script>

</head>
		
<body onload="init()">

