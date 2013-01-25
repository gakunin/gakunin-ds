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

<?php getSearchIdPList(); ?>
	
	var inc_search_list = [ <?php echo $IncSearchList ?> ];
	var favorite_list = [];
	var hint_list = [ <?php echo $IncSearchHintList ?> ];
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
			<a href="http://www.switch.ch/aai"><img src="<?php echo $logoURL ?>" alt="SWITCHaai" id="federationLogo"></a>
			<a href="http://www.switch.ch/"><img src="<?php echo $imageURL ?>/switch-logo.png" alt="SWITCH" id="organisationLogo"></a>
		</div>
			<div id="content">
				<ul class="menu">
				  <li><a href="http://www.switch.ch/<?php echo $language ?>/aai/about/"><?php echo getLocalString('about_federation'); ?></a></li>
				  <li class="last"><a href="http://www.switch.ch/<?php echo $language ?>/aai/faq/"><?php echo getLocalString('faq') ?></a></li>
				  <li class="last"><a href="http://www.switch.ch/<?php echo $language ?>/aai/help/"><?php echo getLocalString('help') ?></a></li>
				  <li class="last"><a href="http://www.switch.ch/<?php echo $language ?>/aai/privacy/"><?php echo getLocalString('privacy') ?></a></li>
				</ul>
<!-- Body: Start -->
