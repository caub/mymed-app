
<div id="create" style="height: 100%;overflow-y:hidden;">
<? include 'notifications.php'; ?>
	<form action="/<?= APP_NAME ?>/login?method=create" method="post" id="loginForm" class="flexCenterVertical" style="padding: 1em;height: 100%;">
		
		<a href="#home" class="btn back">Back</a>
		
		<h1 class="flex1" style="padding-top: 1em;"><?= APP_NAME ?></h1>
		<div class="flex2">
			<input type="email" name="email" placeholder="email" /><br>
			<input type="password" name="password" placeholder="password" /><br>
			<input type="password" name="confirm" placeholder="password confirmation"><br>
			<input type="submit" class="btn btn-primary" value="Register" style="vertical-align: top;">
		</div>
	
	</form>

</div>


