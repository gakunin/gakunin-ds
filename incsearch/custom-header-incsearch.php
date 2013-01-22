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
	<script type="text/javascript" src="<?php echo $ajaxLibURL ?>"></script>
	<script type="text/javascript" src="<?php echo $ajaxFlickLibURL ?>"></script>
	<script type="text/javascript" src="<?php echo $incsearchLibURL ?>"></script>
	<script language="JavaScript" type="text/javascript">
	<!--

<?php
	$selIdP = '';
	$IncSearchArray = array();
	$IncSearchHintArray = array();
	$hintIDPString = addslashes(getLocalString('hint_idp'));

	foreach ($IDProviders as $key => $IDProvider){

		// Get IdP Name
		if (isset($IDProvider[$language]['Name'])){
			$IdPName = addslashes($IDProvider[$language]['Name']);
		} else {
			$IdPName = addslashes($IDProvider['Name']);
		}
		if ($selIdP == ''){
			$selIdP = ($selectedIDP == $key) ? $IdPName : '' ;
		}
		$IdPType = isset($IDProviders[$key]['Type']) ? $IDProviders[$key]['Type'] : '';

		// Skip non-IdP entries
		if ($IdPType == '' || $IdPType == 'category'){
			continue;
		}

                // Get IdP Logo URL and Size
		$IdPLogoURL = '';
		$IdPLogoHeight = '';
		$IdPLogoWidth = '';
		if (isset($IDProvider[$language]['Logo'])){
			$IdPLogoURL = $IDProvider[$language]['Logo']['url'];
			$IdPLogoHeight = $IDProvider[$language]['Logo']['height'];
			$IdPLogoWidth = $IDProvider[$language]['Logo']['width'];
		} elseif (isset($IDProvider['Logo'])) {
			$IdPLogoURL = $IDProvider['Logo']['url'];
			$IdPLogoHeight = $IDProvider['Logo']['height'];
			$IdPLogoWidth = $IDProvider['Logo']['width'];
		}

                // Get GeolocationHint latitude and longitude
		$IdPGeolocationHint = '';
		if (isset($IDProvider['GeolocationHint'])){
			foreach( $IDProvider["GeolocationHint"] as $geolocationhint ) {
				if ($IdPGeolocationHint != '') {
					$IdPGeolocationHint = $IdPGeolocationHint.';'.$geolocationhint;
				} else {
					$IdPGeolocationHint = $geolocationhint;
				}
			}
		}

                // Get Registration URL
                $IdPRegistrationURL = isset($IDProvider['RegistrationURL']) ? $IDProvider['RegistrationURL'] : '';

		$IDProviders2 = $IDProviders;
		$IdPType2 = $IdPType;
		if ($IdPType2 != ''){
			foreach ($IDProviders2 as $key2 => $IDProvider2){
				$incsearchIdPType = isset($IDProviders2[$key2]['Type']) ? $IDProviders2[$key2]['Type'] : '';
				// Skip non-Category
				if ($incsearchIdPType != 'category'){
					continue;
				}
				if ($IdPType == $key2){
					// Get IdP Category Name
					if (isset($IDProvider2[$language]['Name'])){
						$IdPType2 = addslashes($IDProvider2[$language]['Name']);
					} else {
						$IdPType2 = addslashes($IDProvider2['Name']);
					}
					break;
				}
			}
		}

		$SearchIdPName = '';
		foreach ($IDProvider as $attr => $value){
			foreach($langStrings as $lang => $value2){
				if ($attr == $lang){
					if (empty($SearchIdPName)){
						$SearchIdPName = '"'.addslashes($value['Name']).'"';
					} else {
						$SearchIdPName = $SearchIdPName.', "'.addslashes($value['Name']).'"';
					}
					break;
				}
			}
		}
		if (empty($SearchIdPName)){
			$SearchIdPName = '"'.$IdPName.'"';
		}

		$IncSearchArray[] = <<<ENTRY

	[
		"{$key}", "{$IdPType2}", "{$IdPName}", "{$IdPLogoURL}", "{$IdPLogoHeight}", "{$IdPLogoWidth}", "{$IdPGeolocationHint}", "{$IdPRegistrationURL}", "", "", {$SearchIdPName}
	]
ENTRY;

		foreach ($mduiHintIDPs as $hintIDP) {
			if ($key == $hintIDP) {
				$IncSearchHintArray[] = <<<ENTRY

	[
		"{$key}", "{$hintIDPString}", "{$IdPName}", "{$IdPLogoURL}", "{$IdPLogoHeight}", "{$IdPLogoWidth}", "{$IdPGeolocationHint}", "{$IdPRegistrationURL}", "", "", {$SearchIdPName}
	]
ENTRY;

			}
		}
	}
	$IncSearchList = join(',', $IncSearchArray);
	$IncSearchHintList = join(',', $IncSearchHintArray);
	$selIdP = ($selIdP == '') ? getLocalString('select_idp') : $selIdP ;
?>
	
	var inc_search_list = [ <?php echo $IncSearchList ?> ];
	var favorite_list = [];
	var hint_list = [ <?php echo $IncSearchHintList ?> ];
	var initdisp = '<?php echo getLocalString('select_idp') ?>';
	var dispDefault = '<?php echo $selIdP ?>';
	var dispidp = '';
	var hiddenKeyText = '';
	var dropdown_up = '<?php echo $dropdownUpURL ?>';
	var dropdown_down = '<?php echo $dropdownDnURL ?>';
	var geolocation_off = '<?php echo $geolocationOffURL ?>';
	var geolocation_on = '<?php echo $geolocationOnURL ?>';
        var hintmax = '<?php echo $useMduiHintMax ?>';
	var favorite_idp_group = '';
	var hint_idp_group = '<?php echo getLocalString('hint_idp') ?>';
	var wayf_show_categories = true;
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
			for (var i = 0, len = inc_search_list.length; i < len; i++) {
				for (var j = 3, len2 = inc_search_list[i].length; j < len2; j++) {
					var list_idp_name = inc_search_list[i][j].toLowerCase();
					if (idp_name == list_idp_name) {
						document.getElementById('user_idp').value = inc_search_list[i][0];
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
	
	-->
	</script>
	<style type="text/css">
	<!--
	<?php printCSS() ?>
	-->
	</style>
</head>

<body>

<div id="container">
	<div class="box">
		<div id="header">
			<a href="https://www.gakunin.jp/"><img src="<?php echo $logoURL ?>" alt="Federation Logo" id="federationLogo"></a>
		</div>
			<div id="content">
<!-- Body: Start -->
