<?php // Copyright (c) 2011, SWITCH - Serving Swiss Universities ?>

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
   document.write('		<td style="width:100%;">');
   document.write('			<input id="keytext" type="text" name="pattern" value="<?php echo $selIdP ?>" autocomplete="off" size="60" tabindex="5" style="width:100%; display:block;" onclick="searchKeyText(' + "'click'" + '); return false;" />');
   document.write('			<div id="view_incsearch_base">');
   document.write('				<div id="view_incsearch" style="display:none;"></div>');
   document.write('			</div>');
   document.write('		</td>');
   document.write('		<td>');
   document.write('			<a href="" onClick="searchKeyText(' + "'dropdown'" + '); return false;">');
   document.write('				<img id="dropdown_img" src="" title="<?php echo getLocalString('dropdown') ?>" style="border:0px; width:20px; height:20px; vertical-align:middle;">');
   document.write('			</a>');
   document.write('		</td>');
   document.write('		<td>&nbsp;</td>');
   document.write('		<td>');
   document.write('			<input id="wayf_submit_button" type="submit" name="Select" accesskey="s" tabindex="10" value="<?php echo getLocalString('select_button') ?>" ');
   if (noMatch) {
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
		<td colspan="2">
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
		<td style="vertical-align:text-top; text-align:center;">
<script language="JavaScript" type="text/javascript">
<!--
   document.write('			<a href="" style="font-size: 70%;" onClick="searchKeyText(' + "'clear'" + '); return false;"><?php echo getLocalString('clear_button') ?></a>');
-->
</script>
<noscript>
			&nbsp;
</noscript>
		</td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	</tr>
</table>
</form>
<table border="0" cellpadding="1" cellspacing="0">
	<tr>
		<td valign="top" width="14"><img src="<?php echo $imageURL; ?>/arrow-12.gif" alt="arrow"></td>
		<td valign="top"><p class="switchaai"><?php echo getLocalString('switch_description') ?></p></td>
	</tr>
</table>
<!-- Identity Provider Selection: End-->
