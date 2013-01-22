<!DOCTYPE html>
<html > 
<head> 
<meta charset=utf-8>
<title>IDP Geolocation</title>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
	var latitude = '';
	var longitude = '';

	navigator.geolocation.getCurrentPosition(successCallback, errorCallback);

	function createMap(){
		var myLatlng = new google.maps.LatLng(latitude, longitude);
		var myOptions = {
					zoom: 10,
					center: myLatlng,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				}
		var map = new google.maps.Map(document.getElementById("map"), myOptions);
		var marker = new google.maps.Marker({
					position: myLatlng,
					map: map,
					title: 'My Location',
					icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=現|7FFF00|000000' ,
					draggable: true
				});
		google.maps.event.addListener(marker, 'dragstart');
		google.maps.event.addListener(marker, 'dragend', function() {
									displayLatLng(marker.getPosition());
									map.panTo(marker.getPosition());
								});
		google.maps.event.addListener(marker, 'dblclick', function() {
									map.setZoom(17);
									map.setCenter(marker.getPosition());
								});
		for (var i=0; i<document.getElementById("num_geo").value; i++){
			var IDPLocal= new google.maps.LatLng(document.getElementById("geolat_" + i).value, document.getElementById("geolon_" + i).value);
			var markers = new google.maps.Marker({
						position: IDPLocal,
						map: map,
						title: document.getElementById("name_" + i).value ,
						draggable: true
					});
		attachSecretMessage(markers, i);
	}
	
	displayLatLng(marker.getPosition());
	
	}
	
	function successCallback(position) {
		latitude = position.coords.latitude;
		longitude = position.coords.longitude;
		createMap();
	}
	
	function attachSecretMessage(markers, number) {
		google.maps.event.addListener(markers, "click", function() {
			window.location = document.getElementById("regurl_" + number).value;
		});
	}
	
	function displayLatLng(latlng) {
		var str = new String(latlng);
		str = str.replace(" ","");
		str = str.replace("(","");
		str = str.replace(")","");
		jQuery('#latlng').val(str);
	}

	function errorCallback(error) {
		var err_msg = "";
		switch(error.code){
			case 1:
				err_msg = "位置情報の利用が許可されていません";
				break;
			case 2:
				err_msg = "デバイスの位置が判定できません";
				break;
			case 3:
				err_msg = "タイムアウトしました";
				break;
		}
		document.getElementById("show_result").innerHTML = err_msg;
	}
	
</script>
</head>

<body>
<form>

<?php
require_once('config.php');

$sp_samldsurl = $_POST['sp_samldsurl'];
$sp_returnurl = $_POST['sp_returnurl'];

$idplist = explode('||', $_POST['idplist']);
$geo = 0;

foreach ($idplist as $rec) {
	$idpcols = explode(',', $rec);
	if ($idpcols[6]){
		$regurl = $_POST['action'];
		$idppositions = explode(';', $idpcols[6]);
		foreach ($idppositions as $idpposition) {
			$positions = explode(':', $idpposition);

echo <<<printout
	<input type="hidden" id="geolat_$geo" value= "$positions[0]"  />
	<input type="hidden" id="geolon_$geo" value= "$positions[1]" />
	<input type="hidden" id="name_$geo" value= "$idpcols[2]" />
	<input type="hidden" id="regurl_$geo" value= "$sp_samldsurl?entityID=$idpcols[0]&target=$sp_returnurl" />
printout;
			$geo++;
		}
	}
}
?>

        <input type="hidden" id="num_geo" value= "<?php echo $geo; ?>" />

</form>
<table>
   <tr>
   <a href="javascript:history.back();">戻る</a><br /><br />
   </tr>
   <tr>
       <div id="show_result" ></div>
   </tr>
   <tr>
       <!-- setting from config.php -->
       <div align="center" id="map" style="height:500px; margin:0 auto 0 auto;"></div>
   </tr>
</table>

</body> 
</html>
