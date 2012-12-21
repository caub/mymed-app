
<div id="notification" class="<?= isset($other['notification'])?'current':'hidden' ?>" style="z-index: 10;position: absolute;top: 40px;">
	<p id="out" style="margin: 10px;">
		<span><?= isset($other['notification'])?$other['notification']:'' ?></span>
		<a style="text-decoration: none;float: right;" onclick="$(this).parent().parent().hide();">X</a>
	</p>
</div>
