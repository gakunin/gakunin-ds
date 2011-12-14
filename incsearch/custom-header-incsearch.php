<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?php echo getLocalString('title') ?></title> 
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
<style type="text/css">
<!--
	
	body
	{
		color: #000000;
		background-color: #EFF1F1;
		font-family: Verdana, Arial, Helvetica, sans-serif;
		font-size: 12px;
	}
	
	a 
	{
		color: #203781; 
		font-family: Verdana, Arial, Helvetica, sans-serif;
		font-size: 12px;
	}
	
	a:hover
	{
		color: #203781;
		text-decoration: underline;
	}
	
	h2.switchaai
	{
		font-family: Verdana, Arial, Helvetica, sans-serif;
		color: #000000;
		font-size: 17px;
	}
	
	h1
	{
		font-family: Verdana, Arial, Helvetica, sans-serif;
		color: #000000;
		font-size: 18px;
	}
	
	p
	{
		color: #000000;
		font-family: Verdana, Arial, Helvetica, sans-serif;
		line-height: 1.2;
		font-size: 12px;
	}
	
	b
	{
		color: #000000;
		font-family: Verdana, Arial, Helvetica, sans-serif;
		line-height: 1.2;
		font-size: 12px;
		font-weight: bold;
	}
	
	tt
	{
		line-height: 1.2;
		font-weight: bold;
	}
	
	span.switchaai
	{
		line-height: 30px;
	}
	
	input.switchaai
	{
		border-width: 1px;
		border-style: solid;
		border-color: #888888;
	}
	
	a.switchaai
	{
		font-family: Verdana, Arial, Helvetica, sans-serif;
		color: #000000;
		font-size: 12px;
	}
	
	.outer-box
	{
		margin-left:auto; margin-right:auto;
		border-style: solid;
		border-color: #00247D;
		border-width: 1px;
		padding: 10px;
		text-align: left;
		background-color: white;
	}
	
	.selectedIdP
	{
		font-family: Verdana, Arial, Helvetica, sans-serif;
		color: #000000;
		font-size: 12px;
		background-color: white;
		border-color: #203781;
		border-style: solid;
		margin: 2px;
		border-width: 1px;
		width: 400px;
		height: 25px;
		text-align: center;
		line-height: 25px;
	}
	
	.fullheight
	{
		height: 100%;
		min-height: 100%;
	}
	
	.inner-box
	{
		border-width: 1px;
		border-color: #203781;
		background-color: #979CE3;
		border-style: solid;
		padding: 3px;
	}

-->
</style>
<link rel="stylesheet" href="<?php echo $incsearchCssURL ?>" type="text/css" />
<script type="text/javascript" src="<?php echo $ajaxLibURL ?>"></script>
<script type="text/javascript" src="<?php echo $ajaxFlickLibURL ?>"></script>
<script type="text/javascript" src="<?php echo $incsearchLibURL ?>"></script>
<script type="text/javascript" language="javascript">
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
		"{$key}", "{$IdPType2}", "{$IdPName}", {$SearchIdPName}
	]
ENTRY;

		foreach ($mduiHintIDPs as $hintIDP) {
			if ($key == $hintIDP) {
				$IncSearchHintArray[] = <<<ENTRY

	[
		"{$key}", "{$hintIDPString}", "{$IdPName}", {$SearchIdPName}
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
var favorite_idp_group = '';
var hint_idp_group = '<?php echo getLocalString('hint_idp') ?>';
var wayf_show_categories = true;
if (dispDefault == ''){
	dispidp = initdisp;
} else {
	dispidp = dispDefault;
}

-->
</script>

</head>
<body bgcolor="#ffffff" onLoad="if (top != self) {top.location = self.location;};if (document.IdPList && document.IdPList.Select && !document.IdPList.Select.disabled) document.IdPList.Select.focus()">
<script language="JavaScript" type="text/javascript">
<!--
/* Central DS: Selection IdP check */
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

function showConfirmation(){
	
	return confirm(unescape('<?php echo getLocalString('confirm_permanent_selection', 'js') ?>'));
}

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
<table border="0" cellpadding="0" cellspacing="0" style="width:100%; height:100%">
	<tr>
		<td align="center" valign="middle">
			<table border="0" cellpadding="0" cellspacing="0" width="600" class="outer-box">
				<tr>
					<td class="switchaai">
						<a href="https://www.gakunin.jp/" target="_blank"><img src="<?php echo $logoURL ?>" border="0" class="switchaai" alt="Federation Logo"></a>
<!-- Body: Start -->
