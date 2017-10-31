<?php // Copyright (c) 2012, SWITCH - Serving Swiss Universities ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?php echo getLocalString('title') ?></title> 
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="keywords" content="Home Organisation, Discovery Service, WAYF, Shibboleth, Login, AAI">
	<meta name="description" content="Choose your home organisation to authenticate">
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
	
	<link rel="stylesheet" href="<?php echo $incsearchCssURL ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo $geolocationCssURL ?>" type="text/css" />

	<script type="text/javascript" src="<?php echo $ajaxLibURL ?>"></script>
	<script type="text/javascript" src="<?php echo $ajaxFlickLibURL ?>"></script>
	<script type="text/javascript" src="<?php echo $googleMapLibURL."?key=".$googleMapKey ?>"></script>
	<script type="text/javascript" src="<?php echo $geolocationJsURL ?>"></script>
	<script type="text/javascript" src="<?php echo $commonJsURL ?>"></script>
	<script type="text/javascript" src="<?php echo $incsearchLibURL ?>"></script>
	<script language="JavaScript" type="text/javascript">
	<!--

<?php getSearchIdPList(); ?>

	var json_category_list = {<?php echo $JSONIncCategoryList ?>};
	var json_idp_list = [<?php echo $JSONIncIdPList ?>];
	var json_idp_favoritelist = [];
	var json_idp_hintlist = [<?php echo $JSONIncIdPHintList ?>];
	var initdisp = '<?php echo $InitDisp ?>';
	var dispDefault = '<?php echo $selIdP ?>';
	var dispidp = '';
	var hiddenKeyText = '';
	var dropdown_up = '<?php echo $dropdownUpURL ?>';
	var dropdown_down = '<?php echo $dropdownDnURL ?>';
	var geolocation_off = '<?php echo $geolocationOffURL ?>';
	var geolocation_on = '<?php echo $geolocationOnURL ?>';
	var hintmax = '<?php echo $useMduiHintMax ?>';
	var favorite_idp_group = '';
	var hint_idp_group = '<?php echo $hintIDPString ?>';
	var wayf_show_categories = true;
	var discofeedurl = '<?php echo $DiscofeedURL ?>';
	
	var wayfdiv_id = 'container';
	var reg_button = '<?php echo addslashes(getLocalString('reg_button')); ?>';
	var geolocation_err1 = '<?php echo addslashes(getLocalString('geolocation_err1')); ?>';
	var geolocation_err2 = '<?php echo addslashes(getLocalString('geolocation_err2')); ?>';
	var geolocation_err3 = '<?php echo addslashes(getLocalString('geolocation_err3')); ?>';
	var geolocation_err4 = '<?php echo addslashes(getLocalString('geolocation_err4')); ?>';
	var close_button = '<?php echo addslashes(getLocalString('close_button')); ?>';
	var geolocation_button = '<?php echo addslashes(getLocalString('geolocation_button')); ?>';
	var no_hint_msg = '<?php echo addslashes(getLocalString('no_hint_msg')); ?>';
	var no_geolocation_msg = '<?php echo addslashes(getLocalString('no_geolocation_msg')); ?>';
	var near_idp = '<?php echo addslashes(getLocalString('near_idp')); ?>';
	var wayf_googlemap_key = '<?php echo $googleMapKey ?>';
	var wayf_use_disco_feed = false;
	var wayf_additional_idps = [];
	
<?php printJscode_GlobalVariables(); ?>

	// It adds it to window event.
	function start() {
		checkDiscofeed(discofeedurl);
		suggest = new Suggest.Local(
			"keytext",							// element id of input area
			"view_incsearch",					// element id of IdP list display area
			"view_incsearch_animate",			// element id of IdP list display animate area
			"view_incsearch_scroll",			// element id of IdP list display scroll area
			json_idp_list,						// IdP list
			json_idp_favoritelist,				// IdP list (Favorite)
			json_idp_hintlist,					// IdP list (Hint IP, Domain)
			"dropdown_img",						// element id of dropdown image
			"geolocation_img",					// element id of geolocation image
			"wayf_submit_button",				// element id of select button
			"map_a",							// element id of map
			"clear_a",							// element id of clear
			initdisp,							// Initial display of input area
			dispDefault,						// Select IdP display of input area
			dropdown_down,						// URL of deropdown down image
			dropdown_up,						// URL of deropdown up image
			geolocation_off,					// URL of geolocation off image
			geolocation_on,						// URL of geolocation on image
			favorite_idp_group,					// favorite idp list group
			hint_idp_group,						// hint idp list group
			false,								// Embedded or Central Flg
			"optionElm",						// element id of option
			{
				dispMax: 500,					// option display IdP Max
				showgrp: wayf_show_categories	// option show category
			}
		);
	}
	
	window.addEventListener ?
		window.addEventListener('load', start, false) :
		window.attachEvent('onload', start);	

	if (dispDefault == ''){
		dispidp = initdisp;
	} else {
		dispidp = dispDefault;
	}
	
	// Central DS: Selection IdP check
	function checkSelectIdP(){
		
		var idp_name = document.getElementById('keytext').value.toLowerCase();
		var chkFlg = false;
		
		if (hiddenKeyText != '') idp_name = hiddenKeyText.toLowerCase();
		if (initdisp != idp_name) {
			for (var i=0; i<json_idp_list.length; i++){
				for (var j=0; j<json_idp_list[i].search.length; j++){
					var list_idp_name = json_idp_list[i].search[j].toLowerCase();
					if (idp_name == list_idp_name) {
						document.getElementById('user_idp').value = json_idp_list[i].entityid;
						chkFlg = true;
						break;
					}
				}
				if (chkFlg) {
					break;
				}
			}
		}
		return chkFlg;
	}
	
	// Confirm action
	function showConfirmation(){
		
		return confirm(unescape('<?php echo getLocalString('confirm_permanent_selection', 'js') ?>'));
	}
	
	// Perform input validation on WAYF form
	function checkForm(){
		
		var chkFlg = false;
		
		chkFlg = checkSelectIdP();
		if (!chkFlg){
			alert(unescape('<?php echo getLocalString('make_selection', 'js') ?>'));
			return false;
		} else {
			if (document.IdPList.permanent && document.IdPList.permanent.checked){
				return showConfirmation();
			} else {
				return true;
			}
		}
	}
	
	function clone(obj) {
		if (null == obj || "object" != typeof obj) return obj;
		if (typeof Object.assign === 'function')
			return Object.assign({}, obj);
		var copy = obj.constructor();
		for (var attr in obj) {
			if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
		}
		return copy;
	}
	
	-->
	</script>
	<style type="text/css">
	<!--
	<?php printCSS() ?>
	-->
	</style>
</head>

<body>
<div id="wayf_div">
<div id="wayf_mapframe" style="display: none;">
	<div id="mapleft" class="wayf_mframe"></div>
	<div id="mapcenter" class="wayf_mframe"></div>
	<div id="mapright"></div>
</div>
<div id="container">
	<div class="box">
		<div id="header">
			<a href="https://www.gakunin.jp/"><img src="<?php echo $logoURL ?>" alt="Federation Logo" id="federationLogo"></a>
		</div>
			<div id="content">
<!-- Body: Start -->
