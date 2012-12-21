
<div id="home" class="current" style="height: 100%;overflow-y:hidden;">

	<header style="position: absolute;z-index: 1;">
		<h3 style="color: #fff;margin-top: 0px;"><?= APP_NAME ?></h3>
		<a class="btn btn-small btn-info" id="infoButton" href="#profile" style="position: absolute;top: 7px;left: 1em;">Profile</a>
		<button class="btn btn-small" onclick="$('#map_label').toggle()" style="position: absolute;top: 7px;right: 1em;">
			<span class="caret"></span>
	      	<span>Options</span>
    	</button>
	
		
	    <div id="map_label" style="display: none;">
			<span style="margin-right: 1em;">Type:</span> 
			<label class="checkbox inline" style="padding-top: 1px;">
				<input type="checkbox" id="home2" value="home2" onchange="getPOIs();"> home
			</label>
			<label class="checkbox inline" style="padding-top: 1px;">
				<input type="checkbox" id="work2" value="work2" onchange="getPOIs();"> work
			</label>
			<p style="margin: 10px auto 2px;">
				<label class="checkbox inline" style="padding-top: 1px;">
					<input type="checkbox" id="within" onchange="getPOIs();">Within: 
				</label>
				<input onmouseup="selectRange(this)" ontouchend="selectRange(this)"
					id="radius" type="range" placeholder="radius" value="150" min="0.1" max="1000" step="0.1" style="max-width: 100px;vertical-align: middle;"> 
				<span id="radius_value">150</span>
			</p>
		</div>

  
	</header>
	<? include 'notifications.php'; ?>
  	<div id="map_canvas"></div>
  	
</div>

