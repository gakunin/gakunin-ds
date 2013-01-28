<?php
require_once('config.php');
$clientGeolocation[] = array('', '');
if (!empty($_POST['client'])){
	$clientGeolocation = explode(':', $_POST['client']);
}
if (!isset($geolocationMapWidth) || empty($geolocationMapWidth) || $geolocationMapWidth == 'auto') {
	$geolocationMapWidth = '100%';
}
if (!isset($geolocationMapHeight) || empty($geolocationMapHeight)) {
	$geolocationMapHeight = '500px';
}
?>

<!DOCTYPE html>
<html > 
<head> 
<meta charset=utf-8>
<title>IDP Geolocation</title>
<style type="text/css">
<!--
div#view_idpmap {
	text-align:center;
	margin-left:auto;
	margin-right:auto;
}
-->
</style>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="<?php echo $ajaxLibURL ?>"></script>
<script type="text/javascript">
	var latitude = '<?php echo $clientGeolocation[0] ?>';
	var longitude = '<?php echo $clientGeolocation[1] ?>';
	
	function initialize(){
		if ((latitude == '') || (longitude == '')){
			navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
		} else {
			createMap();
		}
	}
	
	function createMap() {
		var myLatlng = new google.maps.LatLng(latitude, longitude);
		var myOptions = {
					zoom: 10,
					center: myLatlng,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				}
		var map = new google.maps.Map(document.getElementById("view_idpmap"), myOptions);
		var marker = new google.maps.Marker({
					position: myLatlng,
					map: map,
					title: 'My Location',
					icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=現|7FFF00|000000' ,
					draggable: true
				});
		google.maps.event.addListener(marker, 'dragstart');
		google.maps.event.addListener(marker, 'dragend', function(){
									displayLatLng(marker.getPosition());
									map.panTo(marker.getPosition());
								});
		google.maps.event.addListener(marker, 'dblclick', function(){
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
	
	function successCallback(position){
		latitude = position.coords.latitude;
		longitude = position.coords.longitude;
		createMap();
	}
	
	function errorCallback(error){
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
	
	function attachSecretMessage(markers, number){
		google.maps.event.addListener(markers, "click", function(){
			document.getElementById("user_idp").value = document.getElementById("idp_" + number).value;
			document.getElementById("userIdP").submit();
		});
	}
	
	function displayLatLng(latlng){
		var str = new String(latlng);
		str = str.replace(" ","");
		str = str.replace("(","");
		str = str.replace(")","");
		jQuery('#latlng').val(str);
	}
	
</script>
</head>

<body onload="initialize()">
<form id="IdPMap">

<?php
$idplist = explode('||', $_POST['idplist']);
$geo = 0;

foreach ($idplist as $rec){
	$idpcols = explode(',', $rec);
	if ($idpcols[6]){
		$idppositions = explode(';', $idpcols[6]);
		foreach ($idppositions as $idpposition){
			$positions = explode(':', $idpposition);
echo <<<printout
	<input type="hidden" id="geolat_$geo" value= "$positions[0]"  />
	<input type="hidden" id="geolon_$geo" value= "$positions[1]" />
	<input type="hidden" id="name_$geo" value= "$idpcols[2]" />
	<input type="hidden" id="idp_$geo" value= "$idpcols[0]" />
printout;
			$geo++;
		}
	}
}
?>

	<input type="hidden" id="num_geo" value= "<?php echo $geo; ?>" />
</form>
<form id="userIdP" method="post" action="<?php echo $_POST['action'] ?>">
	<input type="hidden" id="user_idp" name="user_idp" value="" />
</form>
<table>
   <tr>
   <a href="javascript:history.back();">戻る</a><br /><br />
       <div id="show_result" ></div>
   </tr>
   <tr>
       <div id="view_idpmap" style="width:<?php echo $geolocationMapWidth ?>; height:<?php echo $geolocationMapHeight ?>;"></div>
   </tr>
</table>

</body> 
</html>
