<!-- Identity Provider Selection: Start-->
<h1><?php echo getLocalString('header'); ?></h1> 
<p class="switchaai">
	<?php echo $promptMessage ?>
</p>
<form id="IdPList" name="IdPList" method="post" onSubmit="return checkForm()" action="<?php echo $actionURL ?>">

<script language="JavaScript" type="text/javascript">
<!--
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
   document.write('			<input id="wayf_submit_button" type="submit" name="Select" accesskey="s" tabindex="10" value="<?php echo getLocalString('select_button') ?>" ');
   if (dispidp == initdisp) {
     document.write('disabled >');
   } else {
     document.write('>');
   }
   document.write('		</td>');
-->
</script>
<noscript>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="3">
			<select name="user_idp">
				<option value="-" <?php echo $defaultSelected ?>><?php echo getLocalString('select_idp') ?> ...</option>
				<?php printDropDownList($IDProviders, $selectedIDP) ?>
			</select>
		</td>
		<td>&nbsp;</td>
		<td>
			<input type="submit" name="Select" accesskey="s" tabindex="10" value="<?php echo getLocalString('select_button') ?>" >
		</td>
</noscript>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<td colspan="1">
			<p>
			<input type="checkbox" tabindex="8" <?php echo $rememberSelectionChecked ?> name="session" id="rememberForSession" value="true">
			<span class="warning"><label for="rememberForSession"><?php echo getLocalString('remember_selection') ?></label></span><br>
			<?php if ($showPermanentSetting) : ?>
			<!-- Value permanent must be a number which is equivalent to the days the cookie shall be valid -->
			<input type="checkbox" tabindex="9" name="permanent" id="rememberPermanent" value="100">
			<span class="warning"><label for="rememberPermanent" /><?php echo getLocalString('permanently_remember_selection') ?></label></span>
			<?php endif ?>
			</p>
		</td>
<script language="JavaScript" type="text/javascript">
<!--
   document.write('		<td colspan="2" style="vertical-align:top; text-align:right;">');
   document.write('			<div id="map_a" class="default" title="<?php echo getLocalString('map_tooltip') ?>" tabindex=11><?php echo getLocalString('map_button') ?></div>');
   document.write('		<td colspan="2" style="vertical-align:top; text-align:center;">');
   document.write('			<div id="clear_a" class="default" title="<?php echo getLocalString('clear_tooltip') ?>" tabindex=12><?php echo getLocalString('clear_button') ?></div>');
-->
</script>
<noscript>
		<td colspan="4" style="vertical-align:top; text-align:center;">
			&nbsp;
</noscript>
		</td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	</tr>
</table>
</form>
<form id="GeolocationMap" name="GeolocationMap" method="post" action="<?php echo $geolocationMapURL ?>">
	<input type="hidden" name="idplist" value="">
	<input type="hidden" name="client" value="">
	<input type="hidden" name="sp_samldsurl" value="">
	<input type="hidden" name="sp_returnurl" value="">
<!--
	<input type="hidden" name="action" value="<?php echo  urlencode($actionURL) ?>">
-->
</form>
<?php #phpinfo(); ?>
<p><?php echo getLocalString('additional_info') ?></p>
<!-- Identity Provider Selection: End-->
