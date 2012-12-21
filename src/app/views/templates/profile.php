
<div id="profile" class="selectable" style="height: 100%;">
	<header style="position: absolute;">
		<h3 style="color: #fff;margin-top: 0px;">Profile</h3>
		<a href="#" class="btn  btn-small" style="position: absolute;top: 7px;left: 1em;">Back</a>
		<a class="btn btn-small btn-danger" href="/<?= APP_NAME ?>/logout" style="position: absolute;top: 7px;right: 1em;">Log out</a>
	</header>
	<div class="flexCenterVertical" style="height: 100%;">
		<p><img src="/<?= APP_NAME ?>/img/pin.png" /></p>
		<p><strong>myMap</strong><br>Version 1.0 beta<br></p>
		<br>
		<p><?php foreach ($data as $k=>$v): ?>
		<?= $k ?> => <?= $v ?><br>
		<?php endforeach; ?></p>
	</div>
</div>