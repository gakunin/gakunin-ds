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
   document.write('		<td>');
   document.write('			<input id="keytext" type="text" name="pattern" tabindex="5" value="<?php echo $selIdP ?>" autocomplete="off" size="60" onclick="searchKeyText(' + "'click'" + ');" onBlur="clearListArea();" onFocus="searchKeyText(' + "'focus'" + ');"/>&nbsp;');
   document.write('		</td>');
   document.write('		<td>');
   document.write('			<input id="clearbtn" type="button" name="Clear" accesskey="c" tabindex="7" value="<?php echo getLocalString('clear_button') ?>" onClick="clearKeyText();">&nbsp;');
   document.write('		</td>');
-->
</script>
<noscript>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2">
			<select name="user_idp">
				<option value="-" <?php echo $defaultSelected ?>><?php echo getLocalString('select_idp') ?> ...</option>
				<?php printDropDownList($IDProviders, $selectedIDP) ?>
			</select>
		</td>
</noscript>
		<td>
			<input type="submit" name="Select" accesskey="s" tabindex="10" value="<?php echo getLocalString('select_button') ?>" >
		</td>
	</tr>
	<tr>
		<td  colspan="3">
			<div id="view_incsearch_base">
				<div id="view_incsearch" style="display:none; overflow:hidden; width:400px;" onKeyPress="return submitCheck(event);"></div>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<p>
			<input tabindex="8" type="checkbox" <?php echo $rememberSelectionChecked ?> name="session" id="rememberForSession" value="true">
			<span class="warning"><label for="rememberForSession"><?php echo getLocalString('remember_selection') ?></label></span><br>
			<?php if ($showPermanentSetting) : ?>
			<!-- Value permanent must be a number which is equivalent to the days the cookie shall be valid -->
			<input type="checkbox" tabindex="9" name="permanent" id="rememberPermanent" value="100">
			<span class="warning"><label for="rememberPermanent" /><?php echo getLocalString('permanently_remember_selection') ?></label></span>
			<?php endif ?>
			</p>
		</td>
	</tr>
</table>
</form>
<table border="0" cellpadding="1" cellspacing="0">
	<tr>
		<td valign="top" width="14"><img src="<?php echo $imageURL; ?>/gakunin-seal.png" alt="arrow"></td>
		<td valign="top"><p class="switchaai"><?php echo getLocalString('switch_description') ?></p></td>
	</tr>
</table>
<!-- Identity Provider Selection: End-->
