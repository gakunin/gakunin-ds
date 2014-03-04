jQuery.noConflict();

var null_distance = 9999999999;

// Denshi Kokudo V4
function CJ4BaseMapType() {

        var dataset = [
                null,null,null,null,null,
                "JAIS","JAIS","JAIS","JAIS",
                "BAFD1000K","BAFD1000K","BAFD1000K",
                "BAFD200K","BAFD200K","BAFD200K",
                "DJBMM","DJBMM","DJBMM",
                "FGD"
        ] ;

        CJ4BaseMapType.prototype.tileSize = new google.maps.Size(256,256);
        CJ4BaseMapType.prototype.minZoom = 5;
        CJ4BaseMapType.prototype.maxZoom = 18;
        CJ4BaseMapType.prototype.name = 'CJ4';
        CJ4BaseMapType.prototype.alt = 'Denshi Kokudo V4';

        CJ4BaseMapType.prototype.getTile = function( tileXY, zoom, ownerDocument ) {
                var tileImage = ownerDocument.createElement('img');

                var xID =  tileXY.x + "";
                var yID =  tileXY.y + "";
                xID = "0000000".substr(0, (7- xID.length)) + xID;
                yID = "0000000".substr(0, (7- yID.length)) + yID;

                var fileName = xID + yID + ".png";
                var dir = "";
                for( var i = 0; i < 6; i++ ) {
                        dir += xID.charAt(i) + yID.charAt(i) + "/";
                }

                var url= "http://cyberjapandata.gsi.go.jp/sqras/all/"
                        + dataset[zoom] + "/latest/" + zoom + "/" + dir
                        + fileName;

                tileImage.src = url;
                tileImage.style.width  = this.tileSize.width  + 'px';
                tileImage.style.height = this.tileSize.height + 'px';

                return tileImage;
        };
}

var cj4Type = null;
var cj4LOGO = null;

function clone(obj) { 
    if (null == obj || "object" != typeof obj) return obj; 
    var copy = obj.constructor(); 
    for (var attr in obj) { 
        if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr]; 
    } 
    return copy; 
} 

function keyPressGeoImg(evt) {
    var keyCode;
    if (evt) {
        keyCode = evt.keyCode;
    } else {
        keyCode = event.keyCode;
    }
    if (keyCode != 10 && keyCode != 13) {
        return true;
    } else {
        displayMapIdP(false);
        return false;
    }
}

function addGeoHintList(){
	if (hintmax > json_idp_hintlist.length) {
		var clientLatLng = new Array();
		old_hint_list = new Array();
		for (var i=0; i<json_idp_hintlist.length; i++) {
			old_hint_list.push(clone(json_idp_hintlist[i]));
		}
		hintGeolocationFlg = true;
		if (clientGeolocation == '') {
			getClientGeolocation("list");
		} else {
			clientLatLng = clientGeolocation.split(",");
			checkGeolocation(clientLatLng[0], clientLatLng[1]);
		}
	}
}

function delGeoHintList(){
	json_idp_hintlist = new Array();
	for (var i=0; i<old_hint_list.length; i++) {
		json_idp_hintlist.push(clone(old_hint_list[i]));
	}
	document.getElementById("geolocation_img").src = geolocation_off;
	if (suggest.getInputText() == initdisp) {
		suggest.setInputText('');
	}
	hintGeolocationFlg = false;
	geolocation_flg = false;
	suggest.input.focus();
	suggest.search();
}

function getClientGeolocation(kind){
	if (navigator.geolocation != null && navigator.geolocation != undefined) {
		if (kind == 'list'){
			navigator.geolocation.getCurrentPosition(successGeoCallback, errorGeoCallback);
		} else if (kind == 'map'){
			navigator.geolocation.getCurrentPosition(successGeoCallback2, errorGeoCallback);
		}
	} else {
		alert(geolocation_err1);
	}
}

function successGeoCallback(position){
	checkGeolocation(position.coords.latitude, position.coords.longitude);
}

function successGeoCallback2(position){
	clientGeolocation = position.coords.latitude + ',' + position.coords.longitude;
	document.getElementById("mapleft").innerHTML = '';
	document.getElementById("mapright").innerHTML = '';
	displayMapIdP(true);
}

function errorGeoCallback(error) {
	var err_msg = "";
	geolocation_ngflg = true;
	switch(error.code)
	{
		case 1:
			err_msg = geolocation_err2;
			break;
		case 2:
			err_msg = geolocation_err3;
			break;
		case 3:
			err_msg = geolocation_err4;
			break;
	}
	document.getElementById("geolocation_img").src = geolocation_off;
	alert(err_msg);
}

function checkGeolocation(clientIdo, clientKeido) {
	var distance = 0;
	var lastdistance = 0;
	var geohint_list = [];
	var geokyori_list = [];
	var tmp_idp_list = [];

	clientGeolocation = clientIdo + ',' + clientKeido;
	for (var i=0; i<json_idp_list.length; i++){
		var min_kyori = null_distance;
		if ( json_idp_list[i].geolocation.length > 0) {
			var cur_kyori = 0;
			var latlon = [];
			var doubleFlg = false;
			for (var j=0; j<json_idp_hintlist.length; j++){
				if (json_idp_list[i].entityid == json_idp_hintlist[j].entityid){
					doubleFlg = true;
					break;
				}
			}
			if (!doubleFlg){
				for (var j=0; j<json_idp_list[i].geolocation.length; j++){
					latlon = json_idp_list[i].geolocation[j].split(",");
					cur_kyori = getDistance(clientIdo, clientKeido, latlon[0], latlon[1], 10);
					if (min_kyori > cur_kyori){
						min_kyori = cur_kyori;
					}
				}
				json_idp_list[i].distance = min_kyori;
				geohint_list.push(clone(json_idp_list[i]));
			}
		}
	}
	geohint_list.sort(function(a, b){
		var x = a.distance;
		var y = b.distance;
		if (x > y) return 1;
		if (x < y) return -1;
		return 0;
	});
	for (var i=0; i<geohint_list.length; i++){
		if (hintmax > json_idp_hintlist.length && geohint_list[i].distance != null_distance){
			geohint_list[i].categoryName = hint_idp_group;
			json_idp_hintlist.push(clone(geohint_list[i]));
		}
	}
	document.getElementById("geolocation_img").src = geolocation_on;
	if (suggest.getInputText() == initdisp) {
		suggest.setInputText('');
	}
	geolocation_flg = false;
	geolocation_ngflg = false;
	suggest.input.focus();
	suggest.search();
}

function getDistance(lat1, lng1, lat2, lng2, precision) {
	var distance = 0;
	if ((Math.abs(lat1 - lat2) < 0.00001) && (Math.abs(lng1 - lng2) < 0.00001)) {
		distance = 0;
	} else {
		lat1 = lat1 * Math.PI / 180;
		lng1 = lng1 * Math.PI / 180;
		lat2 = lat2 * Math.PI / 180;
		lng2 = lng2 * Math.PI / 180;
		
		var A = 6378140;
		var B = 6356755;
		var F = (A - B) / A;
		
		var P1 = Math.atan((B / A) * Math.tan(lat1));
		var P2 = Math.atan((B / A) * Math.tan(lat2));

		var X = Math.acos(Math.sin(P1) * Math.sin(P2) + Math.cos(P1) * Math.cos(P2) * Math.cos(lng1 - lng2));
		var L = (F / 8) * ((Math.sin(X) - X) * Math.pow((Math.sin(P1) + Math.sin(P2)), 2) / Math.pow(Math.cos(X / 2), 2) - (Math.sin(X) - X) * Math.pow(Math.sin(P1) - Math.sin(P2), 2) / Math.pow(Math.sin(X), 2));
		
		distance = A * (X + L);
		var decimal_no = Math.pow(10, precision);
		distance = Math.round(decimal_no * distance / 1) / decimal_no / 1000;
		distance = Math.round(distance * 10);
		distance = distance / 10;
	}
	return distance;
}

function displayMapIdP(clientCenterFlg){
	var mapframe = document.getElementById("mapframe");
	createList();
	jQuery(document).ready(function($){
		$('#' + mapframe.id).slideDown(1000);
	});
	createMap(clientCenterFlg);
}

function hiddenMapIdP(){
	var wayfframe = document.getElementById(wayfdiv_id);
	wayfframe.style.display = '';
	var mapframe = document.getElementById("mapframe");
	mapframe.style.display = 'none';
	document.getElementById("mapleft").innerHTML = '';
	document.getElementById("mapright").innerHTML = '';
}

function moveMapCenter(latlng, zoom, name){
	var arrayLatLng = new Array();
	if (latlng == ''){
		latlng = json_category_list["others"].geolocation;
	}
	arrayLatLng = latlng.split(",");
	myMap.setCenter(new google.maps.LatLng(arrayLatLng[0], arrayLatLng[1]));
	myMap.setZoom(zoom);
	if (name != ''){
		openInfoWindow(name);
	}
}

function changeButtonColor(objid, classNm){
	var targetObj = document.getElementById(objid);
	targetObj.className = classNm;
}

function changeListColor(objid, baseClassNm, chgClassNm){
	var targetObj = document.getElementById(objid);
	targetObj.className = baseClassNm + ' ' + chgClassNm;
}

function selectMapIdP(name){
	hiddenMapIdP();
	suggest.setInputText(name);
	suggest.oldText = name;
	suggest.input.focus();
	if (suggest.suggestList){
		suggest.search();
	}
	suggest.selectElm.disabled = false;
}

function createList(){
	var hintIdPName = '';
	var hintCategoryKey = '';
	var elmLeft_frm = document.getElementById("mapleft");
	var clientLatLng = new Array();
	var elm_blk = document.createElement("br");
	var elmLeft_title = document.createElement("div");
	
	elmLeft_title.id = 'titlehint';
	elmLeft_title.className = 'title';
	elmLeft_title.innerHTML = '&nbsp;' + hint_idp_group;
	elmLeft_frm.appendChild(elmLeft_title);
	
	if (old_hint_list.length > 0){
		hintIdPName = old_hint_list[0].name;
		hintCategoryKey = old_hint_list[0].categoryKey;
	} else if (!hintGeolocationFlg && json_idp_hintlist.length > 0){
		hintIdPName = json_idp_hintlist[0].name;
		hintCategoryKey = json_idp_hintlist[0].categoryKey;
	}
	
	if (hintIdPName == ''){
		var elmLeft_msg = document.createElement("div");
		elmLeft_msg.className = 'listhint';
		elmLeft_msg.style.textalign = 'left';
		elmLeft_msg.style.verticalalign = 'middle';
		elmLeft_msg.innerHTML = no_hint_msg;
		elmLeft_frm.appendChild(elmLeft_msg);
	} else {
		for (var i=0; i<json_idp_list.length; i++){
			if (hintIdPName == json_idp_list[i].name){
				var elmLeft_list = document.createElement("div");
				elmLeft_list.id = "listhint";
				elmLeft_list.className = 'listhint';
				elmLeft_list.style.cursor = 'pointer';
				elmLeft_list.innerHTML = json_idp_list[i].name;
				elmLeft_list.setAttribute('onmouseover', 'changeListColor("listhint", "listhint", "mouseover");return false;');
				elmLeft_list.setAttribute('onmouseout', 'changeListColor("listhint", "listhint", "mouseout");return false;');
				elmLeft_list.setAttribute('onclick', 'moveMapCenter("' + json_category_list[hintCategoryKey].geolocation + '", ' +
																		json_category_list[hintCategoryKey].mapscale + ', "' +
																		json_idp_list[i].name + '"); return false;');
				elmLeft_list.onmouseover = function() {changeListColor(this.id, "listhint", "mouseover");return false;};
				elmLeft_list.onmouseout = function() {changeListColor(this.id, "listhint", "mouseout");return false;};
				elmLeft_list.onclick = function() {moveMapCenter(json_category_list[hintCategoryKey].geolocation,
																json_category_list[hintCategoryKey].mapscale,
																hintIdPName); return false;};
				elmLeft_frm.appendChild(elmLeft_list);
				break;
			}
		}
	}
	elmLeft_frm.appendChild(elm_blk);
	
	if (clientGeolocation != ''){
		clientLatLng = clientGeolocation.split(",");
	}
	
	var elmLeft_title = document.createElement("div");
	elmLeft_title.id = 'titlegeolocation';
	elmLeft_title.className = 'title';
	elmLeft_title.innerHTML = '&nbsp;' + near_idp;
	elmLeft_frm.appendChild(elmLeft_title);
	
	if (clientLatLng.length == 0){
		var elmLeft_msg = document.createElement("div");
		elmLeft_msg.className = 'listhint';
		elmLeft_msg.style.textalign = 'left';
		elmLeft_msg.style.verticalalign = 'middle';
		elmLeft_msg.innerHTML = no_geolocation_msg;
		elmLeft_frm.appendChild(elmLeft_msg);
	} else {
		var geoIdPList = new Array();
		for (var i=0; i<json_idp_list.length; i++){
			var min_kyori = null_distance;
			for (var j=0; j<json_idp_list[i].geolocation.length; j++){
				var latlon = json_idp_list[i].geolocation[j].split(",");
				var cur_kyori = getDistance(clientLatLng[0], clientLatLng[1], latlon[0], latlon[1], 10);
				if (min_kyori > cur_kyori){
					min_kyori = cur_kyori;
				}
			}
			json_idp_list[i].distance = min_kyori;
			geoIdPList.push(clone(json_idp_list[i]));
		}
		
		geoIdPList.sort(function(a, b){
			var x = a.distance;
			var y = b.distance;
			if (x > y) return 1;
			if (x < y) return -1;
			return 0;
		});
		
		for (var i = 0; i < 10; i++){
			if (geoIdPList[i].distance == null_distance){
				break;
			} 
			var elmLeft_list = document.createElement("div");
			var elmLeft_list_name = document.createElement("div");
			elmLeft_list_name.id = "name" + i;
			elmLeft_list_name.className = 'list';
			elmLeft_list_name.style.cursor = 'pointer';
			elmLeft_list_name.innerHTML = geoIdPList[i].name;
			elmLeft_list_name.setAttribute('onmouseover', 'changeListColor("name' + i + '", "list",  "mouseover");return false;');
			elmLeft_list_name.setAttribute('onmouseout', 'changeListColor("name' + i + '", "list", "mouseout");return false;');
			elmLeft_list_name.setAttribute('onclick', 'moveMapCenter("' + json_category_list[geoIdPList[i].categoryKey].geolocation + '", ' + 
																		json_category_list[geoIdPList[i].categoryKey].mapscale + ', "' +
																		geoIdPList[i].name + '"); return false;');
			elmLeft_list_name.onmouseover = function() {changeListColor(this.id, "list", "mouseover");};
			elmLeft_list_name.onmouseout = function() {changeListColor(this.id, "list", "mouseout");return false;};
			elmLeft_list_name.onclick = function() {moveMapCenter(json_category_list[geoIdPList[this.id.slice(4)].categoryKey].geolocation,
																json_category_list[geoIdPList[this.id.slice(4)].categoryKey].mapscale,
																geoIdPList[this.id.slice(4)].name); return false;};
			elmLeft_list.appendChild(elmLeft_list_name);
			var elmLeft_list_distance = document.createElement("div");
			elmLeft_list_distance.id = "distance" + i;
			elmLeft_list_distance.className = 'distance';
			elmLeft_list_distance.style.cursor = 'pointer';
			elmLeft_list_distance.innerHTML =  geoIdPList[i].distance + " km";
			elmLeft_list_distance.setAttribute('onmouseover', 'changeListColor("name' + i + '", "list", "mouseover");return false;');
			elmLeft_list_distance.setAttribute('onmouseout', 'changeListColor("name' + i + '", "list", "mouseout");return false;');
			elmLeft_list_distance.setAttribute('onclick', 'moveMapCenter("' + json_category_list[geoIdPList[i].categoryKey].geolocation + '", ' +
																			json_category_list[geoIdPList[i].categoryKey].mapscale + ', "' +
																			geoIdPList[i].name + '"); return false;');
			elmLeft_list_distance.onmouseover = function() {changeListColor("name" + this.id.slice(8) , "list", "mouseover");};
			elmLeft_list_distance.onmouseout = function() {changeListColor("name" + this.id.slice(8) , "list", "mouseout");return false;};
			elmLeft_list_distance.onclick = function() {moveMapCenter(json_category_list[geoIdPList[this.id.slice(8)].categoryKey].geolocation, 
																		json_category_list[geoIdPList[this.id.slice(8)].categoryKey].mapscale, 
																		geoIdPList[this.id.slice(8)].name); return false;};
			elmLeft_list.appendChild(elmLeft_list_distance);
			elmLeft_frm.appendChild(elmLeft_list);
		}
	}
	
	var elmRight_frm = document.getElementById("mapright");
	var elmRight_close = document.createElement("div");
	elmRight_close.id = 'mapclose';
	elmRight_close.className = 'mframe mouseout';
	elmRight_close.innerHTML = close_button;
	elmRight_close.setAttribute('onmouseover', 'changeButtonColor("mapclose", "mframe mouseover");return false;');
	elmRight_close.setAttribute('onmouseout', 'changeButtonColor("mapclose", "mframe mouseout");return false;');
	elmRight_close.setAttribute('onClick', 'hiddenMapIdP();return false;');
	elmRight_close.onmouseover = function() {changeButtonColor("mapclose","mframe mouseover");return false;};
	elmRight_close.onmouseout = function() {changeButtonColor("mapclose","mframe mouseout");return false;};
	elmRight_close.onclick = function() {hiddenMapIdP();return false;};
	elmRight_frm.appendChild(elmRight_close);
	
	elmRight_frm.appendChild(elm_blk);
	
	var elmRight_geolocation = document.createElement("div");
	elmRight_geolocation.id = 'mapgeolocation';
	elmRight_geolocation.className = 'mframe mouseout';
	elmRight_geolocation.innerHTML = geolocation_button;
	elmRight_geolocation.setAttribute('onmouseover', 'changeButtonColor("mapgeolocation", "mframe mouseover");return false;');
	elmRight_geolocation.setAttribute('onmouseout', 'changeButtonColor("mapgeolocation", "mframe mouseout");return false;');
	elmRight_geolocation.setAttribute('onclick', 'getClientGeolocation("map");return false;');
	elmRight_geolocation.onmouseover = function() {changeButtonColor("mapgeolocation","mframe mouseover");return false;};
	elmRight_geolocation.onmouseout = function() {changeButtonColor("mapgeolocation","mframe mouseout");return false;};
	elmRight_geolocation.onclick = function() {getClientGeolocation("map");return false;};
	elmRight_frm.appendChild(elmRight_geolocation);
}

function createMap(clientCenterFlg){
	var mapcenter = document.getElementById("mapcenter");
	var hintIdPName = '';
	var hintCategoryKey = '';
	var arrayLatLng = new Array();
	var clientLatLng = new Array();

	if (old_hint_list.length > 0){
		hintIdPName = old_hint_list[0].name;
		hintCategoryKey = old_hint_list[0].categoryKey;
	} else if (!hintGeolocationFlg && json_idp_hintlist.length > 0){
		hintIdPName = json_idp_hintlist[0].name;
		hintCategoryKey = json_idp_hintlist[0].categoryKey;
	} else {
		hintIdPName = '';
		hintCategoryKey = 'others';
	}
	
	if (clientGeolocation != ''){
		clientLatLng = clientGeolocation.split(",");
	}

	if (clientCenterFlg){
		var min_kyori = null_distance;
		for (var i=0; i<json_category_list.length; i++){
			var latlon = json_category_list[i].geolocation.split(",");
			var cur_kyori = getDistance(clientLatLng[0], clientLatLng[1], latlon[0], latlon[1], 10);
			if (min_kyori > cur_kyori){
				min_kyori = cur_kyori;
				hintCategoryKey = i;
			}
		}
		moveMapCenter(json_category_list[hintCategoryKey].geolocation, json_category_list[hintCategoryKey].mapscale, '');
	} else {
		arrayLatLng = json_category_list[hintCategoryKey].geolocation.split(",");
		myMap = new google.maps.Map(mapcenter, {
											zoom: json_category_list[hintCategoryKey].mapscale,
											center: new google.maps.LatLng(arrayLatLng[0], arrayLatLng[1]),
											scaleControl: true,
											mapTypeControl: false,
											streetViewControl: false,
											mapTypeId: 'cj4'
										}
									);
                if (!cj4Type) cj4Type = new CJ4BaseMapType();
		myMap.mapTypes.set( 'cj4', cj4Type );
		
		// Denshi Kokudo LOGO
		cj4LOGO = document.createElement('div');
		cj4LOGO.innerHTML = "<a href='http://portal.cyberjapan.jp/' target='_blank' ><img src='http://cyberjapan.jp/images/icon01.gif' width='32' height='32' alt='Denshi Kokudo'></a>";
		cj4LOGO.style.display = "inline";
		myMap.controls[ google.maps.ControlPosition.BOTTOM_LEFT ].push(cj4LOGO);
		
		for (var i=0; i<json_idp_list.length; i++){
			var openFlg = false;
			if (hintIdPName == json_idp_list[i].name){
				openFlg = true;
			}
			for (var j=0; j<json_idp_list[i].geolocation.length; j++){
				if (json_idp_list[i].geolocation[j] != '') {
					latlng = json_idp_list[i].geolocation[j].split(",");
					var marker = new google.maps.Marker({
						position: new google.maps.LatLng(latlng[0], latlng[1]),
						title: json_idp_list[i].name,
						map: myMap
					});
					markersList[json_idp_list[i].name] = marker;
					var content = '';
					if (json_idp_list[i].logoURL != ''){
						content += '<img src="' + json_idp_list[i].logoURL + '" style="height:35px;" /><br /><br />';
					}
					content += '<div id="idplist' + i + 
							'" class="default"' +
							' onmouseover="changeButtonColor(' + "this.id, 'active');return false;" + '"' +
							' onmouseout="changeButtonColor(' + "this.id, 'default');return false;" + '"' +
							' onclick="selectMapIdP(' + "'" + json_idp_list[i].name + "'" + ');return false;">' +
							json_idp_list[i].name +
							'</div>';
					attachMessage(marker, content, json_idp_list[i].name, openFlg);
				}
			}
		}
		
	}
	if (clientLatLng.length > 0){
		if (typeof markersList["GEOLOCATION"] != "undefined"){
			markersList["GEOLOCATION"].setMap(null);
		}
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(clientLatLng[0], clientLatLng[1]),
			map: myMap,
			icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=|7FFF00|000000'
		});
		markersList["GEOLOCATION"] = marker;
	}
}

function attachMessage(marker, msg, name, open){
	var infowindow = new google.maps.InfoWindow({
		// message
		content: msg
	});
	infowindowsList[name] = infowindow;

	if (open){
		openInfoWindow(name);
	}
	google.maps.event.addListener(marker,'click',function(){
		deleteInfoWindow();
		infowindow.open(marker.getMap(), marker);
	});
}

function openInfoWindow(name) {
	if (name != '' && 
	    typeof markersList[name] != "undefined" &&
	    typeof infowindowsList[name] != "undefined"){
		var marker = markersList[name];
		var infowindow = infowindowsList[name];
		deleteInfoWindow();
		infowindow.open(marker.getMap(), marker);
	}
}

// Deletes all markers in the array by removing references to them
function deleteInfoWindow() {
	if (infowindowsList) {
		for (var i=0; i<infowindowsList.length; i++) { infowindowsList[i].close(); }
	}
}
