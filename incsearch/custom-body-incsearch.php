<!-- Identity Provider Selection: Start-->
<h1><?php echo getLocalString('header'); ?></h1> 
<form id="IdPList" name="IdPList" method="post" onSubmit="return checkForm()" action="<?php echo $actionURL ?>">
	<div id="userInputArea">
		<p class="promptMessage"><?php echo $promptMessage ?></p>
<script language="JavaScript" type="text/javascript">
<!--
   var reg_button = '<?php echo addslashes(getLocalString('reg_button')); ?>';
   var geolocation_err1 = '<?php echo addslashes(getLocalString('geolocation_err1')); ?>';
   var geolocation_err2 = '<?php echo addslashes(getLocalString('geolocation_err2')); ?>';
   var geolocation_err3 = '<?php echo addslashes(getLocalString('geolocation_err3')); ?>';
   var geolocation_err4 = '<?php echo addslashes(getLocalString('geolocation_err4')); ?>';
   document.write('<input id="user_idp" type="hidden" name="user_idp" value=""/>');
   document.write('<table border="0" cellpadding="0" cellspacing="0">');
   document.write('	<tr>');
   document.write('		<td colspan="1" style="width:100%;">');
   document.write('			<input id="keytext" type="text" name="pattern" value="" autocomplete="off" size="60" tabindex="5" style="width:100%; display:block;" />');
   document.write('			<div id="view_incsearch_base">');
   document.write('				<div id="view_incsearch_animate">');
   document.write('					<div id="view_incsearch_scroll">');
   document.write('						<div id="view_incsearch"></div>');
   document.write('					</div>');
   document.write('				</div>');
   document.write('			</div>');
   document.write('		</td>');
   document.write('		<td>');
   document.write('			<img id="dropdown_img" src="" title="<?php echo getLocalString('dropdown_tooltip') ?>" tabindex=6 style="border:0px; width:20px; height:20px; vertical-align:middle;">');
   document.write('		</td>');
   document.write('		<td>');
   document.write('			<img id="geolocation_img" src="" title="<?php echo getLocalString('geolocation_tooltip') ?>" tabindex=7 style="border:0px; width:20px; height:20px; vertical-align:middle;">');
   document.write('		</td>');
   document.write('		<td>&nbsp;</td>');
   document.write('		<td>');
   document.write('			<input id="wayf_submit_button" type="submit" name="Select" accesskey="s" tabindex="19" value="<?php echo getLocalString('select_button') ?>" ');
   if (dispidp == initdisp) {
     document.write('disabled >');
   } else {
     document.write('>');
   }
   document.write('		</td>');
   document.write('		<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>');
   document.write('	</tr>');
   document.write('	<tr>');
   document.write('		<td colspan="1" style="font-size: 80%;">');
<?php
  $tabindex = 8;
  foreach ($IDProvidersKind as $key => $IDProviderKind){
    $IdPType = isset($IDProvidersKind[$key]['Type']) ? $IDProvidersKind[$key]['Type'] : '';
    if ($IdPType != 'kind'){ continue; }
    if (isset($IDProviderKind[$language]['Name'])){
      $IdPKindName = addslashes($IDProviderKind[$language]['Name']);
    } else {
      $IdPKindName = addslashes($IDProviderKind['Name']);
    }
    $IdPKindChecked = $IDProviderKind['Default'];
    print("document.write('                     <input type=\"radio\" tabindex=$tabindex name=\"kindgroup\" value=\"$key\" onclick=\"changeKind();\" $IdPKindChecked>$IdPKindName</input>');\n");
    $tabindex++;
  }
?>
   document.write('		</td>');
   document.write('		<td colspan="2" style="vertical-align:top; text-align:right;">');
   document.write('			<div id="map_a" class="default" title="<?php echo getLocalString('map_tooltip') ?>" tabindex=15><?php echo getLocalString('map_button') ?></div>');
   document.write('		<td colspan="2" style="vertical-align:top; text-align:center;">');
   document.write('			<div id="clear_a" class="default" title="<?php echo getLocalString('clear_tooltip') ?>" tabindex=16><?php echo getLocalString('clear_button') ?></div>');
   document.write('		</td>');
   document.write('		<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>');
   document.write('	</tr>');
   document.write('	<tr>');
   document.write('		<td colspan="6">');
   document.write('			<p>');
   document.write('			<input type="checkbox" tabindex="17" <?php echo $rememberSelectionChecked ?> name="session" id="rememberForSession" value="true">');
   document.write('			<span class="warning"><label for="rememberForSession"><?php echo getLocalString('remember_selection') ?></label></span><br>');
   document.write('			<?php if ($showPermanentSetting) : ?>');
   document.write('			<!-- Value permanent must be a number which is equivalent to the days the cookie shall be valid -->');
   document.write('			<input type="checkbox" tabindex="18" name="permanent" id="rememberPermanent" value="100">');
   document.write('			<span class="warning"><label for="rememberPermanent" /><?php echo getLocalString('permanently_remember_selection') ?></label></span>');
   document.write('			<?php endif ?>');
   document.write('			</p>');
   document.write('		</td>');
   document.write('	</tr>');
   document.write('</table>');
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
<form id="GeolocationMap" method="post" action="<?php echo $geolocationMapURL ?>">
	<input type="hidden" id="idplist" name="idplist" value="">
	<input type="hidden" id="client" name="client" value="">
	<input type="hidden" id="action" name="action" value="<?php echo $actionURL ?>">
</form>

<p><?php echo getLocalString('additional_info') ?></p>
<!-- Identity Provider Selection: End-->
