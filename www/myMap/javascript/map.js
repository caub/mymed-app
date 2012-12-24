var map;

var latlng; // the center point if the map

var markers = {}; //list of markers on the map

var rad = 1.35; //radius of search

var pinMarker, pinInfo;

//var accessToken; //backend API accessToken, set via PHP
//var app_name;// set via PHP
//var backend; // set via PHP

var earthCells = '0123456789abcdef'.split('');

var latlng;

var pageDefault = "home";

var source;

window.onload = init;
window.onhashchange = locationHashChanged;

function init() {
	
	if ('EventSource' in window){
		source = new EventSource(/*backend+*/ '/inbox/stream'); //native EventSource don't support CORS, have to use https://github.com/Yaffle/EventSource for that
		source.onmessage = function(e) {
			// XSS in chat is fun
			entry = JSON.parse(e.data);
			if (entry.type === "message" && entry.channel === 'chat'){
				document.getElementById('notification').style.display = 'block';
				//alert(msg.data);
				var data = JSON.parse(entry.data);
				$('#out').prepend($('<span>['+data.user+'] '+': '+ htmlEntities(data.message)+'</span>'));
				
			}else if (entry.type === "message"){
				document.getElementById('notification').style.display = 'block';
				//alert(msg.data);
				var data = JSON.parse(entry.data);
				var link = $('<a href="#?id='+data.id+'" onclick="popup(this)">New publication!</a>');
				
				var desc = $('<span style="margin-left: 10px;">'+decodeURIComponent(data.text)+'...'+'</span>');

				$('#out').prepend(desc).prepend(link).prepend($('<span>[notification (python gunicorn)]: </span>'));

			}
			else{
				//alert('subscribed');
			}
		};
		
		source2 = new EventSource(/*backend+*/ '/mymed_backend/notifications'); //native EventSource don't support CORS, have to use https://github.com/Yaffle/EventSource for that
		source2.onmessage = function(e) {
			// XSS in chat is fun
			entry = JSON.parse(e.data);
		
			if (!entry.type){
				document.getElementById('notification').style.display = 'block';

				var link = $('<a href="#?id='+entry.id+'" onclick="popup(this)">New publication!</a>');
				
				var desc = $('<span style="margin-left: 10px;">'+decodeURIComponent(entry.text)+'...'+'</span>');

				$('#out').prepend(desc).prepend(link).prepend($('<span>[notification (glassfish)]: </span>'));
			}

		};
	}
	
	//maps

	markerList = {};

	map = new google.maps.Map(document.getElementById("map_canvas"), {
		mapTypeId : google.maps.MapTypeId.ROADMAP,
		zoom: 5
	});
	
	latlng = new google.maps.LatLng('43.616548616564', '7.0685118565707');
	map.setCenter(latlng);

	pinMarker = new google.maps.Marker({
		map : map,
		draggable: true,
		icon: '/'+app_name+'/img/pin'
	});

	pinMarker.setPosition(new google.maps.LatLng(latlng.lat()-6, latlng.lng()-14));

	pinInfo = new google.maps.InfoWindow({
		content : "<p>Move me and leave a <b>message</b>!</p> " +
		"<p><label class='checkbox inline' style='padding-top: 1px;'><input type='checkbox' id='home_'>home</label>"+
		"<label class='checkbox inline' style='padding-top: 1px;'><input type='checkbox' id='work'>work</label></p>"+
		"<p><textarea id='content'></textarea></p>"+
		"<a onclick='addPOI();' class='btn btn-small btn-primary btn-block'>Save</a>" 
	});
	pinInfo.open(map, pinMarker);
	google.maps.event.addListener(pinMarker, 'click', function(event) {
		pinInfo.open(map, pinMarker);
	});
	/*google.maps.event.addListener(pinMarker, 'dragend', function(event) {
		console.log('marker ' + event.latLng.lat() + ' ' + event.latLng.lng());
	});*/

	google.maps.event.addListener(map, 'drag', updateRectangle);

	google.maps.event.addListener(map, 'dragend', function() {
		
		updateRectangle();
		if ($('#within').attr('checked')){
			getPOIs();
		}
		
	});

	rectangle = new google.maps.Rectangle();
	var rectOptions = {
		strokeColor : "#FF0000",
		strokeOpacity : 0.8,
		strokeWeight : 2,
		fillOpacity : 0,
		map : map
	};
	
	rectangle.setOptions(rectOptions);
	
	//initial req
	updateRectangle();
	getPOIs();
	locationHashChanged();
	
	if (navigator.geolocation) {
		navigator.geolocation.watchPosition(displayPosition, displayError, {enableHighAccuracy : true, timeout: 5000, maximumAge: 0});
	}

}

function popup(el){
	if (location.hash === el.href.substr(el.href.indexOf('#'))){ //trigger hashchange manually
		locationHashChanged();
	}
}

function updateRectangle(){
	var center = map.getCenter();
	var c = Math.cos(center.lat()* Math.PI / 180);
	rectangle.setBounds(new google.maps.LatLngBounds(
			new google.maps.LatLng(center.lat()-c*rad, center.lng()-rad),
			new google.maps.LatLng(center.lat()+c*rad, center.lng()+rad)));
}


function getPOIs(){
	if (!$('#within').attr('checked')){
		rectangle.setVisible(false);
		getPois(earthCells);
	}else{
		rectangle.setVisible(true);
		var coords = [
	          rectangle.getBounds().getSouthWest().lat(),
	          rectangle.getBounds().getSouthWest().lng(),
	          rectangle.getBounds().getNorthEast().lat(),
	          rectangle.getBounds().getNorthEast().lng()
		];
		$.getJSON(backend+'/GeoCellHandler?position='+coords.join('|'), function(response){
			getPois(response.dataObject.results);
		});
	}
}

function getPois(cells){
	
	clearOverlays();
	
	var types = [];
	
	//alphabetical order
	if ($('#home2').attr('checked'))
		types.push("home");
	if ($('#work2').attr('checked'))
		types.push("work");
	
	var predicates = [];
	for( var i in cells){
		predicates.push({"position": [cells[i]], "type": types});
	}

	$.getJSON(backend+'/PublishHandler?code=1&application='+app_name+'&accessToken='+accessToken+'&predicates='+JSON.stringify(predicates),
	function(res){
		//console.log(res);
		if (res.status==200){
			//console.log(res.dataObject.results.length);
			for (var i in res.dataObject.results){
				var item = res.dataObject.results[i];
				if (!$('#within').attr('checked') || rectangle.getBounds().contains(new google.maps.LatLng(item.lat, item.lng)))
					addMarker(item.lat, item.lng, item.type, item.id, item.content);
			}
		}
	});


}


function addPOI(){

	var types = [];
	
	//alphabetical order
	if ($('#home_').attr('checked'))
		types.push("home");
	if ($('#work').attr('checked'))
		types.push("work");
	
	
	var coords = [
	    pinMarker.getPosition().lat(),
	    pinMarker.getPosition().lng()
	];

	var metadata = {}, data = {};
	
	metadata.lat = data.lat = coords[0];
	metadata.lng = data.lng = coords[1];
	metadata.time = data.time = parseInt(new Date().getTime()/1000);
	metadata.type = data.type = types.join(', ');
	
	data.text = encodeURIComponent($('#content').val());
	data.author = user_name;
	
	$.getJSON(backend+'/GeoCellHandler?position='+coords.join('|'), function(response){
		cells = response.dataObject.results;
		
		var predicates = [];
		for( var i in cells){
			predicates.push({"position": [cells[i]], "type": types});
		}
		$.post(backend+'/PublishHandler',
			{
				code: 0,
				application: app_name,
				accessToken: accessToken,
				metadata: JSON.stringify(metadata),
				data: JSON.stringify(data),
				predicates: JSON.stringify(predicates)
			}, function(res){
				//console.log(res);
				getPOIs();
		});
		
	});

}

function delPOI(id){

	//console.log(id);

	$.getJSON(backend+'/PublishHandler?code=3&application='+app_name+'&accessToken='+accessToken+'&id='+id,
		function(res){
			//console.log(res);
			if (!$('#within').attr('checked')){
				getPOIs();
			}
	});
	
}

function getDetails(id){

	$.getJSON(backend+'/PublishHandler?code=1&application='+app_name+'&accessToken='+accessToken+'&id='+id,
		function(res){
			if (res.status==200){
				var item = res.dataObject.details;
				var infowindow = new google.maps.InfoWindow({
					content: (item.author ? "<p><b>"+item.author+"</b></p>" : "") +
								"<p>" + decodeURIComponent(item.text||"vide...")+"</p>"+
								"<p><a href='#' onclick='delPOI(\""+id+"\");'>delete</a></p>"
				});
				if (!(id in markers)){
					addMarker(item.lat, item.lng, item.type, item.id, item.content);
				}
				infowindow.open(map, markers[id]);
				map.setCenter(new google.maps.LatLng(item.lat, item.lng));
			}
	});

}

function addMarker(lat, lng, type, id, content){
	
	//check if  markers contains this id
	if (id in markers){
		markers[id].setMap(map);
		return;
	}
	
	var marker = new google.maps.Marker({
		map : map,
		position: new google.maps.LatLng(lat, lng)
	});
	if (type.indexOf("home")>=0){
		if (type.indexOf("work")>=0)
			marker.setIcon('/'+app_name+'/img/homework');
		else
			marker.setIcon('/'+app_name+'/img/home');
	} else if (type.indexOf("work")>=0)
		marker.setIcon('/'+app_name+'/img/work');
	
	var infowindow = new google.maps.InfoWindow({
		content : "<p style='font-weight:bold;'>"+type+"</p><p>(" + lat+","+lng+"</p><p>"+
		(content||"")+"</p><p>"+
		"<a href='#' onclick='getDetails(\""+id+"\");'>details...</a> or <a href='#' onclick='delPOI(\""+id+"\");'>delete...</a></p>"
	});
	//infowindow.open(map, marker);
	google.maps.event.addListener(marker, 'click', function(event) {
		if (location.hash === '#?id='+id){
			locationHashChanged(); //manual trigger
		} else {
			location.hash = '#?id='+id;
		}
	});
	
	markers[id] = marker;
}

function setAllMap(map) {
	for (var i in markers) {
		markers[i].setMap(map);
	}
}
// Removes the overlays from the map, but keeps them in the array.
function clearOverlays() {
	setAllMap(null);
}
// Shows any overlays currently in the array.
function showOverlays() {
	setAllMap(map);
}

// Deletes all markers in the array by removing references to them.
function deleteOverlays() {
	clearOverlays();
	markers = {};
}

function htmlEntities(str) {
	return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}


function refresh(){
	google.maps.event.trigger(map, 'resize');
	map.setCenter(latlng);
}

function selectRange(el){
	rad = parseFloat($(el).val()*180/(Math.PI*6371));
	$('#radius_value').html(el.value);
	google.maps.event.trigger(map, 'dragend');
}


function displayPosition(position) {
	latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
	map.setCenter(latlng);
	pinMarker.setPosition(latlng);
	if (position.coords.accuracy) {
		console.log("acc: " + position.coords.accuracy);
	}
}
function displayError(error) {
	var errors = {
		1 : 'Denied permission',
		2 : 'Position not available',
		3 : 'Expired request'
	};
	console.log("geolocation error: " + errors[error.code]);
}


function locationHashChanged() {

	var parts = location.hash.split('?'); //remove subquery contained in hash
	var page = parts[0].substr(1);
	if (location.href.indexOf('method=')>=0 && location.hash==""){
	}
	page = route(page);
	
	if (parts.length>1){
		var req = parseQ(parts[1]);
		if ('id' in req){
			getDetails(req.id);
		}
	}

	$('.current').removeClass('current');
	$('#'+page).addClass('current');
	
	//for the map
	if (map)
		google.maps.event.trigger(map, 'dragend');
}

function route( page ){
	var pages =  $('body > *');
	for (var i=0; i<pages.length; i++){
		if (page === pages[i].id){
			return page;
		}
	}
	return pageDefault;
}


function parseQ( s ){
    var r = {};

    var qs = s.split('&');
    for (var i=0; i<qs.length; i++){
        var p = qs[i].split('=');
        r[p[0]] = p[1];
    }
    return r;
}

function buildQ( o ){
	var r = '?';
    for (var i in o){
        r+=i+'='+o[i]+'&';
    }
    r= r.substr(0, r.length - 1);
    return r;
}

