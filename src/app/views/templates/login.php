
<div id="home" class="current" style="height: 100%;overflow-y:hidden;">

<? include 'notifications.php'; ?>

	<form action="/<?= APP_NAME ?>/login" method="post" class="flexCenterVertical" style="padding: 1em;height: 100%;">
		<a class="btn btn-info" id="infoButton" href="#about">About</a>
		
		<h1 class="flex1" style="padding-top: 1em;"><?= APP_NAME ?></h1>
		
		
		<div class="flex2">
			<input type="text" name="login" placeholder="Email" value="test_myapp@yopmail.com">
			<input type="password" name="password" placeholder="Password" value="1">
			<input type="submit" class="btn btn-primary" value="Log in" style="vertical-align: top;">
			<a href="#create" class="btn" style="vertical-align: top;">Register</a>
		</div>
	</form>


</div>


