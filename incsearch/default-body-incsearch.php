<?php // Copyright (c) 2011, SWITCH - Serving Swiss Universities ?>

<!-- Identity Provider Selection: Start-->
<h1><?php echo getLocalString('header'); ?></h1> 
<p class="switchaai">
	<?php echo $promptMessage ?>
</p>

<form id="IdPList" name="IdPList" method="post" onSubmit="return checkForm()" action="<?php echo $actionURL ?>">
<input id="user_idp" type="hidden" name="user_idp" value=""/>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<input id="keytext" type="text" name="pattern" tabindex="5" value="<?php echo $selIdP ?>" autocomplete="off" size="60" onclick="searchKeyText('click');" onBlur="clearListArea();" onFocus="searchKeyText('focus');"/>&nbsp;
		</td>
		<td>
			<input id="clearbtn" type="button" name="Clear" accesskey="c" tabindex="7" value="<?php echo getLocalString('clear_button') ?>" onClick="clearKeyText();">&nbsp;
		</td>
		<td>
			<input type="submit" name="Select" accesskey="s" tabindex="10" value="<?php echo getLocalString('select_button') ?>" >
		</td>
	</tr>
	<tr>
		<td  colspan="3">
			<div id="view_incsearch" style="display:none; overflow:hidden; width:400px;" onKeyPress="return submitCheck(event);"></div>
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
		<td valign="top" width="14"><img src="<?php echo $imageURL; ?>/arrow-12.gif" alt="arrow"></td>
		<td valign="top"><p class="switchaai"><?php echo getLocalString('switch_description') ?></p></td>
	</tr>
</table>
<!-- Identity Provider Selection: End-->
