<!-- Identity Provider Selection: Start-->
<h1><?php echo getLocalString('header'); ?></h1> 
<form id="IdPList" name="IdPList" method="post" onSubmit="return checkForm()" action="<?php echo $actionURL ?>">
	<div id="optionElm" style="display:none;"></div>
	<div id="userInputArea">
		<p class="promptMessage"><?php echo $promptMessage ?></p>
<script language="JavaScript" type="text/javascript">
<!--
   document.write('<div class="wayf_userInputArea">');
   document.write('<div class="wayf_col">');
   document.write('<div class="wayf_col">');
   document.write('<div class="wayf_radioArea">');
   document.write('<div class="wayf_row">');
   document.write('<div class="wayf_optionTitle">');
   document.write('<?php echo getLocalString('locations_filter') ?>');
   document.write('</div>');
   document.write('<div class="wayf_optionRadio">');
<?php
  $ua=$_SERVER['HTTP_USER_AGENT'];
  $browser=((strpos($ua,'iPhone')!==false)||(strpos($ua,'iPod')!==false)||(strpos($ua,'Android')!==false));
?>
<?php
  if($browser=='sp') {
    $deviceType = 'mobile';
  } else {
    $deviceType = 'other';
  }
  $tabindex = 3;
  $idindex = 0;
  if ($deviceType == 'mobile'){
    print("   document.write('                  <select name=\"locationgroup\" tabindex=$tabindex onchange=\"changeLocation_sel();\">');\n");
  }
  foreach ($IDProviders as $key => $IDProviderLocation){
    $IdPType = isset($IDProviders[$key]['Type']) ? $IDProviders[$key]['Type'] : '';
    if ($IdPType != 'category'){ continue; }
    if (isset($IDProviderLocation[$language]['Name'])){
      $IdPLocationName = addslashes($IDProviderLocation[$language]['Name']);
    } else {
      $IdPLocationName = addslashes($IDProviderLocation['Name']);
    }
    if ($IdPLocationName == $otherFederationString || $IdPLocationName == 'Unknown'){ continue; }
    if (isset($IDProviderLocation['Default'])){
      $IdPLocationChecked = $IDProviderLocation['Default'];
    }
    $idindex++;
    if ($deviceType != 'mobile'){
      print("   document.write('                  <div class=\"wayf_row\">');\n");
      print("   document.write('                  <input type=\"radio\" id=\"location$idindex\" tabindex=$tabindex name=\"locationgroup\" value=\"$key\" onclick=\"changeLocation();\" $IdPLocationChecked/>');\n");
      print("   document.write('                  <label for=\"location$idindex\" class=\"wayf_label_option\">$IdPLocationName</label>');\n");
      print("   document.write('                  </div>');\n");
    } else {
      print("   document.write('                  <option id=\"location$idindex\" name=\"locationgroup\" value=\"$key\" $IdPLocationChecked>$IdPLocationName</option>');\n");
    }
  }
  if ($deviceType == 'mobile'){
    print("   document.write('                  </select>');\n");
  }
  $tabindex++;
?>
   document.write('</div>');
   document.write('</div>');
   document.write('<div class="wayf_row">');
   document.write('<div class="wayf_optionTitle">');
   document.write('<?php echo getLocalString('category_filter') ?>');
   document.write('</div>');
   document.write('<div class="wayf_optionRadio">');
<?php
  $idindex = 0;
  if ($deviceType == 'mobile'){
    print("   document.write('                  <select name=\"kindgroup\" tabindex=$tabindex onchange=\"changeKind_sel();\">');\n");
  }
  foreach ($IDProvidersKind as $key => $IDProviderKind){
    $IdPType = isset($IDProvidersKind[$key]['Type']) ? $IDProvidersKind[$key]['Type'] : '';
    if ($IdPType != 'kind'){ continue; }
    if (isset($IDProviderKind[$language]['Name'])){
      $IdPKindName = addslashes($IDProviderKind[$language]['Name']);
    } else {
      $IdPKindName = addslashes($IDProviderKind['Name']);
    }
    if (isset($IDProviderKind['Default'])){
      $IdPKindChecked = $IDProviderKind['Default'];
    }
    $idindex++;
    if ($deviceType != 'mobile'){
      print("   document.write('                  <div class=\"wayf_row\">');\n");
      print("   document.write('                  <input type=\"radio\" id=\"kind$idindex\" tabindex=$tabindex name=\"kindgroup\" value=\"$key\" onclick=\"changeKind();\" $IdPKindChecked/>');\n");
      print("   document.write('                  <label for=\"kind$idindex\" class=\"wayf_label_option\">$IdPKindName</label>');\n");
      print("   document.write('                  </div>');\n");
    } else {
      print("   document.write('                  <option id=\"kind$idindex\" name=\"kindgroup\" value=\"$key\" $IdPKindChecked/>$IdPKindName</option>');\n");
    }
  }
  if ($deviceType == 'mobile'){
    print("   document.write('                  </select>');\n");
  }
  $tabindex++;
?>
   document.write('</div>');
   document.write('</div>');
   document.write('</div>');
   document.write('<div class="wayf_inputArea">');
   document.write('<div class="wayf_inputtext">');
   document.write('	<input id="user_idp" type="hidden" name="user_idp" value=""/>');
   document.write('	<input id="keytext" type="text" name="pattern" value="" autocomplete="off" size="60" tabindex="5" />');
   document.write('	<div id="view_incsearch_base">');
   document.write('		<div id="view_incsearch_animate">');
   document.write('			<div id="view_incsearch_scroll">');
   document.write('				<div id="view_incsearch"></div>');
   document.write('			</div>');
   document.write('		</div>');
   document.write('	</div>');
   document.write('</div>');
   document.write('<div class="wayf_eventItem">');
   document.write('			<img id="dropdown_img" src="" title="<?php echo getLocalString('dropdown_tooltip') ?>" tabindex=6 />');
   document.write('</div>');
   document.write('<div class="wayf_eventItem">');
   document.write('			<img id="geolocation_img" src="" title="<?php echo getLocalString('geolocation_tooltip') ?>" tabindex=7 />');
   document.write('</div>');
   document.write('<div id="wayf_submit_div" class="wayf_eventItem">');
   document.write('	<input id="wayf_submit_button" type="submit" name="Select" accesskey="s" tabindex="19" value="<?php echo getLocalString('select_button') ?>" ');
   if (dispidp == initdisp) {
     document.write('disabled >');
   } else {
     document.write('>');
   }
   document.write('</div>');
   document.write('</div>');
   document.write('<div class="wayf_row">');
   document.write('<div class="wayf_checkArea">');
   document.write('<div class="wayf_optionCheck">');
   document.write('<div class="wayf_row">');
   document.write('			<input type="checkbox" tabindex="17" <?php echo $rememberSelectionChecked ?> name="session" id="rememberForSession" value="true">');
   document.write('</div>');
   document.write('<div class="wayf_row">');
   document.write('			<span class="warning"><label for="rememberForSession"><?php echo getLocalString('remember_selection') ?></label></span><br>');
   document.write('</div>');
   document.write('</div>');
   document.write('			<?php if ($showPermanentSetting) : ?>');
   document.write('<div class="wayf_optionCheck">');
   document.write('<div class="wayf_row">');
   document.write('			<!-- Value permanent must be a number which is equivalent to the days the cookie shall be valid -->');
   document.write('			<input type="checkbox" tabindex="18" name="permanent" id="rememberPermanent" value="100">');
   document.write('</div>');
   document.write('<div class="wayf_row">');
   document.write('			<span class="warning"><label for="rememberPermanent" /><?php echo getLocalString('permanently_remember_selection') ?></label></span>');
   document.write('</div>');
   document.write('</div>');
   document.write('			<?php endif ?>');
   document.write('</div>');
   document.write('<div class="wayf_linkArea">');
   document.write('<div class="wayf_col">');
   if (wayf_googlemap_key != ''){
     document.write('                     <a href="javascript:void(0)" id="map_a" title="<?php echo getLocalString('map_tooltip') ?>" tabindex=15><?php echo getLocalString('map_button') ?></a>');
   } else {
     document.write('                     <div id="map_a"></div>');
   }
   document.write('</div>');
   document.write('<div class="wayf_col">');
   document.write('                     <a href="javascript:void(0)" id="clear_a" title="<?php echo getLocalString('clear_tooltip') ?>" tabindex=16><?php echo getLocalString('clear_button') ?></a>');
   document.write('</div>');
   document.write('</div>');
   document.write('</div>');
   document.write('</div>');
   document.write('</div>');
   document.write('</div>');
-->
</script>
<noscript>
		<div align="center">
			<select name="user_idp" id="userIdPSelection"> 
				<option value="-" <?php echo $defaultSelected ?>><?php echo getLocalString('select_idp') ?> ...</option>
			<?php printDropDownList($IDProviders, $selectedIDP) ?>
			</select>
			<input type="submit" name="Select" accesskey="s" value="<?php echo getLocalString('select_button') ?>"> 
		</div>
		<div align="left">
			<p class="selectOptions">
				<input type="checkbox" <?php echo $rememberSelectionChecked ?> name="session" id="rememberForSession" value="true">
				<label for="rememberForSession"><?php echo getLocalString('remember_selection') ?></label><br>
				<?php if ($showPermanentSetting) : ?>
				<!-- Value permanent must be a number which is equivalent to the days the cookie shall be valid -->
				<input type="checkbox" name="permanent" id="rememberPermanent" value="100">
				<label for="rememberPermanent" /><?php echo getLocalString('permanently_remember_selection') ?></label>
				<?php endif ?>
			</p>
		</div>
</noscript>
</div>
</form>

<p><?php echo getLocalString('additional_info') ?></p>
</div>
<!-- Identity Provider Selection: End-->
