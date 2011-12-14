<?php // Copyright (c) 2011, SWITCH - Serving Swiss Universities

/******************************************************************************/

function printEmbeddedWAYFScript_IncSearch(){

	global $langStrings, $language, $imageURL, $logoURL, $smallLogoURL, $federationURL;
	global $selectedIDP, $IDProviders, $redirectCookieName, $redirectStateCookieName, $federationName, $cookieSecure;
	global $safekind, $selIdP, $incsearchLibURL, $incsearchCssURL, $alertURL, $dropdownUpURL, $dropdownDnURL, $ajaxLibURL, $ajaxFlickLibURL;
	global $mduiHintIDPs;
	
	// Get some values that are used in the script
	$loginWithString = getLocalString('login_with');
	$makeSelectionString = getLocalString('make_selection', 'js');
	$loggedInString =  getLocalString('logged_in');
	$configurationScriptUrl = preg_replace('/embedded-wayf.js/', 'embedded-wayf.js/snippet.html', 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	$utcTime = time();
	$checkedBool = (isset($_COOKIE[$redirectStateCookieName]) && !empty($_COOKIE[$redirectStateCookieName])) ? 'checked="checked"' : '' ;
	$rememberSelectionText = addslashes(getLocalString('remember_selection'));
	$loginString = addslashes(getLocalString('login'));
	$selectIdPString = addslashes(getLocalString('select_idp'));
	$otherFederationString = addslashes(getLocalString('other_federation'));
	$mostUsedIdPsString = addslashes(getLocalString('most_used'));
	$clearString = addslashes(getLocalString('clear_button'));
	$alertspTooltip = addslashes(getLocalString('alertsp_tooltip'));
	$dropdownTooltip = addslashes(getLocalString('dropdown_tooltip'));
	$clearTooltip = addslashes(getLocalString('clear_tooltip'));
	$hintIDPString = addslashes(getLocalString('hint_idp'));
	
	$selIdP = '';

	// Generate list of Identity Providers
	$JSONIdPArray = array();
	
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
		
		// SSO
		if (isset($IDProvider['SSO'])){
			$IdPSSO = $IDProvider['SSO'];
		} else {
			$IdPSSO = '';
		}
		
		// Skip non-IdP entries
		if ($IdPType == '' || $IdPType == 'category'){
			continue;
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

		$IncSearchIDP = <<<ENTRY
"{$key}", "{$IdPType2}", "{$IdPName}", {$SearchIdPName}
ENTRY;

		$JSONIdPArray[] = <<<ENTRY

	"{$key}":{
		type:"{$IdPType}",
		name:"{$IdPName}",
		search:[{$IncSearchIDP}],
		SAML1SSOurl:"{$IdPSSO}"
		}
ENTRY;
	}
	$JSONIdPList = join(',', $JSONIdPArray);
	$IdPHintList = '';
	foreach ($mduiHintIDPs as $hintIDP){
		if (empty($IdPHintList)) {
			$IdPHintList = '"'.$hintIDP.'"';
		} else {
			$IdPHintList = $IdPHintList.', "'.$hintIDP.'"';
		}
	}
	$InitDisp = getLocalString('select_idp');
	
	echo <<<SCRIPT

// To use this JavaScript, please access:
// {$configurationScriptUrl}
// and copy/paste the resulting HTML snippet to an unprotected web page that 
// you want the embedded WAYF to be displayed

// ############################################################################

// Declare all variables
var wayf_sp_entityID;
var wayf_URL;
var wayf_return_url;
var wayf_sp_handlerURL;

var wayf_use_discovery_service;
var wayf_use_small_logo;
var wayf_width;
var wayf_height;
var wayf_background_color;
var wayf_border_color;
var wayf_font_color;
var wayf_font_size;
var wayf_hide_logo;
var wayf_auto_login;
var wayf_logged_in_messsage;
var wayf_hide_after_login;
var wayf_most_used_idps;
var wayf_show_categories;
var wayf_hide_categories;
var wayf_hide_idps;
var wayf_unhide_idps;
var wayf_show_remember_checkbox;
var wayf_force_remember_for_session;
var wayf_additional_idps;
var wayf_discofeed_url;
var wayf_sp_cookie_path;
var wayf_list_height;
var wayf_sp_samlDSURL;
var wayf_sp_samlACURL;
var wayf_html = "";
var wayf_idps = { {$JSONIdPList} };
var wayf_hint_list = [ {$IdPHintList} ];
var inc_search_list = [];
var favorite_list = [];
var hint_list = [];
var submit_check_list = [];
var safekind = '{$safekind}';
var allIdPList = '';
var initdisp = '{$InitDisp}';
var dispDefault = '{$selIdP}';
var dispidp = '';
var hiddenKeyText = '';
var dropdown_up = '{$dropdownUpURL}';
var dropdown_down = '{$dropdownDnURL}';
var favorite_idp_group = "{$mostUsedIdPsString}";
var hint_idp_group = '{$hintIDPString}';

// Define functions
function submitForm(){

	var NonFedEntityID;
	var idp_name = document.getElementById('keytext').value.toLowerCase();
	var chkFlg = false;
	if (hiddenKeyText != '') idp_name = hiddenKeyText.toLowerCase();

	if (inc_search_list.length > 0) {
		submit_check_list = inc_search_list;
	}
	if (favorite_list.length > 0) {
		submit_check_list = favorite_list.concat(submit_check_list);
	}
	if (hint_list.length > 0) {
		submit_check_list = hint_list.concat(submit_check_list);
	}
	
	for (var i=0; i<submit_check_list.length; i++){
		for (var j = 3, len2 = submit_check_list[i].length; j < len2; j++) {
			var list_idp_name = submit_check_list[i][j].toLowerCase();
			if (idp_name == list_idp_name){
				NonFedEntityID = submit_check_list[i][0];
				document.getElementById('user_idp').value = submit_check_list[i][0];
				chkFlg = true;
				if (safekind > 0 && safekind != 3){
					// Store SAML domain cookie for this foreign IdP
					setCookie('_saml_idp', encodeBase64(submit_check_list[i][0]) , 100);
				}
				break;
                	}
		}
		if (chkFlg) {
			break;
		}
        }
        if (!chkFlg){
                alert('{$makeSelectionString}');
                return false;
        }

        // User chose non-federation IdP
        // TODO: FIX windows error
        // 4 >= (8 - 3/4)
        if (
                i >= (submit_check_list.length - wayf_additional_idps.length)){

                var redirect_url;

                // Store SAML domain cookie for this foreign IdP
                setCookie('_saml_idp', encodeBase64(NonFedEntityID) , 100);

                // Redirect user to SP handler
                if (wayf_use_discovery_service){
                        redirect_url = wayf_sp_samlDSURL + '?entityID='
                        + encodeURIComponent(NonFedEntityID)
                        + '&target=' + encodeURIComponent(wayf_return_url);

                        // Make sure the redirect always is being done in parent window
                        if (window.parent){
                                window.parent.location = redirect_url;
                        } else {
                                window.location = redirect_url;
                        }

                } else {
                        redirect_url = wayf_sp_handlerURL + '?providerId='
                        + encodeURIComponent(NonFedEntityID)
                        + '&target=' + encodeURIComponent(wayf_return_url);

                        // Make sure the redirect always is being done in parent window
                        if (window.parent){
                                window.parent.location = redirect_url;
                        } else {
                                window.location = redirect_url;
                        }

                }

                // If input type button is used for submit, we must return false
                return false;
        } else {
		if (safekind == 0 || safekind == 3){
			// delete local cookie
			setCookie('_saml_idp', encodeBase64(submit_check_list[i][0]), -1);
		}
                // User chose federation IdP entry
                document.IdPList.submit();
        }
        return false;
}

function writeHTML(a){
	wayf_html += a;
}

function pushIncSearchList(IdP){
	inc_search_list.push(wayf_idps[IdP].search.slice());
	for(var i in wayf_hint_list){
		if (wayf_hint_list[i] == IdP) {
			hint_list.push(wayf_idps[IdP].search.slice());
			hint_list[hint_list.length - 1][1] = hint_idp_group;
		}
	}
}

function isAllowedType(IdP, type){
	for ( var i=0; i<= wayf_hide_categories.length; i++){
		
		if (wayf_hide_categories[i] == type || wayf_hide_categories[i] == "all" ){
			
			for ( var i=0; i <= wayf_unhide_idps.length; i++){
				// Show IdP if it has to be unhidden
				if (wayf_unhide_idps[i] == IdP){
					return true;
				}
			}
			// If IdP is not unhidden, the default applies
			return false;
		}
	}
	
	// Category was not hidden
	return true;
}

function isAllowedCategory(category){
	
	if (!category || category == ''){
		return true;
	}
	
	for ( var i=0; i<= wayf_hide_categories.length; i++){
		
		if (wayf_hide_categories[i] == category || wayf_hide_categories[i] == "all" ){
			return false;
		}
	}
	
	// Category was not hidden
	return true;
}

function isAllowedIdP(IdP){
	
	for ( var i=0; i<=wayf_hide_idps.length; i++){
		if (wayf_hide_idps[i] == IdP){
			return false;
		}
	}
	// IdP was not hidden
	return true;
}

function setCookie(c_name, value, expiredays){
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + expiredays);
	document.cookie=c_name + "=" + escape(value) +
	((expiredays==null) ? "" : "; expires=" + exdate.toGMTString()) +
	((wayf_sp_cookie_path=="") ? "" : "; path=" + wayf_sp_cookie_path)
SCRIPT;
	if( isset($cookieSecure) )
	{
		echo <<<SCRIPT
 + "; secure";
SCRIPT;
	}
	else
	{
		echo <<<SCRIPT
;
SCRIPT;
	}
	echo <<<SCRIPT

}

function getCookie(check_name){
	// First we split the cookie up into name/value pairs
	// Note: document.cookie only returns name=value, not the other components
	var a_all_cookies = document.cookie.split( ';' );
	var a_temp_cookie = '';
	var cookie_name = '';
	var cookie_value = '';
	
	for ( var i = 0; i < a_all_cookies.length; i++ )
	{
		// now we'll split apart each name=value pair
		a_temp_cookie = a_all_cookies[i].split( '=' );
		
		
		// and trim left/right whitespace while we're at it
		cookie_name = a_temp_cookie[0].replace(/^\s+|\s+$/g, '');
	
		// if the extracted name matches passed check_name
		if ( cookie_name == check_name )
		{
			// We need to handle case where cookie has no value but exists (no = sign, that is):
			if ( a_temp_cookie.length > 1 )
			{
				cookie_value = unescape( a_temp_cookie[1].replace(/^\s+|\s+$/g, '') );
			}
			// note that in cases where cookie is initialized but no value, null is returned
			return cookie_value;
			break;
		}
		a_temp_cookie = null;
		cookie_name = '';
	}
	
	return null;
}

// Checks if there exists a cookie containing check_name in its name
function isCookie(check_name){
	// First we split the cookie up into name/value pairs
	// Note: document.cookie only returns name=value, not the other components
	var a_all_cookies = document.cookie.split( ';' );
	var a_temp_cookie = '';
	var cookie_name = '';
	
	for ( var i = 0; i < a_all_cookies.length; i++ ){
		// now we'll split apart each name=value pair
		a_temp_cookie = a_all_cookies[i].split( '=' );
		
		// and trim left/right whitespace while we're at it
		cookie_name = a_temp_cookie[0].replace(/^\s+|\s+$/g, '');
		
		// if the extracted name matches passed check_name
		
		if ( cookie_name.search(check_name) >= 0){
			return true;
		}
	}
	
	// Shibboleth session cookie has not been found
	return false;
}

function encodeBase64(input) {
	var base64chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
	var output = "", c1, c2, c3, e1, e2, e3, e4;
	
	for ( var i = 0; i < input.length; ) {
		c1 = input.charCodeAt(i++);
		c2 = input.charCodeAt(i++);
		c3 = input.charCodeAt(i++);
		e1 = c1 >> 2;
		e2 = ((c1 & 3) << 4) + (c2 >> 4);
		e3 = ((c2 & 15) << 2) + (c3 >> 6);
		e4 = c3 & 63;
		if (isNaN(c2)){
			e3 = e4 = 64;
		} else if (isNaN(c3)){
			e4 = 64;
		}
		output += base64chars.charAt(e1) + base64chars.charAt(e2) + base64chars.charAt(e3) + base64chars.charAt(e4);
	}
	
	return output;
}

function decodeBase64(input) {
	var base64chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
	var output = "", chr1, chr2, chr3, enc1, enc2, enc3, enc4;
	var i = 0;

	// Remove all characters that are not A-Z, a-z, 0-9, +, /, or =
	var base64test = /[^A-Za-z0-9\+\/\=]/g;
	input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
	
	do {
		enc1 = base64chars.indexOf(input.charAt(i++));
		enc2 = base64chars.indexOf(input.charAt(i++));
		enc3 = base64chars.indexOf(input.charAt(i++));
		enc4 = base64chars.indexOf(input.charAt(i++));

		chr1 = (enc1 << 2) | (enc2 >> 4);
		chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
		chr3 = ((enc3 & 3) << 6) | enc4;

		output = output + String.fromCharCode(chr1);

		if (enc3 != 64) {
			output = output + String.fromCharCode(chr2);
		}
		if (enc4 != 64) {
			output = output + String.fromCharCode(chr3);
		}
		
		chr1 = chr2 = chr3 = "";
		enc1 = enc2 = enc3 = enc4 = "";
		
	} while (i < input.length);
	
	return output;
}

(function() {
	
	var config_ok = true; 
	
	// First lets make sure properties are available
	if(
		typeof(wayf_use_discovery_service)  == "undefined"  
		|| typeof(wayf_use_discovery_service) != "boolean"
	){
		wayf_use_discovery_service = true;
	}
	
	if(typeof(wayf_sp_entityID) == "undefined"){
		alert('The mandatory parameter \'wayf_sp_entityID\' is missing. Please add it as a javascript variable on this page.');
		config_ok = false;
	}
	
	if(typeof(wayf_URL) == "undefined"){
		alert('The mandatory parameter \'wayf_URL\' is missing. Please add it as a javascript variable on this page.');
		config_ok = false;
	}
	
	if(typeof(wayf_return_url) == "undefined"){
		alert('The mandatory parameter \'wayf_return_url\' is missing. Please add it as a javascript variable on this page.');
		config_ok = false;
	}

	if(typeof(wayf_discofeed_url) == "undefined"){
		wayf_discofeed_url = '';
	}

	if(typeof(wayf_sp_cookie_path) == "undefined"){
		wayf_sp_cookie_path = '';
	}
	
	if((typeof(wayf_list_height) != "number") || (wayf_list_height < 0)){
		wayf_list_height = '150px';
	} else {
		wayf_list_height += 'px';
	}

	if(wayf_use_discovery_service == false && typeof(wayf_sp_handlerURL) == "undefined"){
		alert('The mandatory parameter \'wayf_sp_handlerURL\' is missing. Please add it as a javascript variable on this page.');
		config_ok = false;
	}
	
	if(wayf_use_discovery_service == true && typeof(wayf_sp_samlDSURL) == "undefined"){
		// Set to default DS handler
		wayf_sp_samlDSURL = wayf_sp_handlerURL + "/DS";
	}
	
	if (typeof(wayf_sp_samlACURL) == "undefined"){
		wayf_sp_samlACURL = wayf_sp_handlerURL + '/SAML/POST';
	}
	
	if(typeof(wayf_font_color) == "undefined"){
		wayf_font_color = 'black';
	}
	
	if(
		typeof(wayf_font_size) == "undefined"
		|| typeof(wayf_font_size) != "number"
		){
		wayf_font_size = 12;
	}
	
	if(typeof(wayf_border_color) == "undefined"){
		wayf_border_color = '#00247D';
	}
	
	if(typeof(wayf_background_color) == "undefined"){
		wayf_background_color = '#F4F7F7';
	}
	
	if(
		typeof(wayf_use_small_logo) == "undefined" 
		|| typeof(wayf_use_small_logo) != "boolean"
		){
		wayf_use_small_logo = false;
	}
	
	if(
		typeof(wayf_hide_logo) == "undefined" 
		|| typeof(wayf_use_small_logo) != "boolean"
		){
		wayf_hide_logo = false;
	}
	
	if(typeof(wayf_width) == "undefined"){
		wayf_width = "auto";
	} else if (typeof(wayf_width) == "number"){
		wayf_width += 'px';
	}
	
	if(typeof(wayf_height) == "undefined"){
		wayf_height = "auto";
	} else if (typeof(wayf_height) == "number"){
		wayf_height += "px";
	}
	
	if(
		typeof(wayf_show_remember_checkbox) == "undefined"
		|| typeof(wayf_show_remember_checkbox) != "boolean"
		){
		wayf_show_remember_checkbox = true;
	}
	
	if(
		typeof(wayf_force_remember_for_session) == "undefined"
		|| typeof(wayf_force_remember_for_session) != "boolean"
		){
		wayf_force_remember_for_session = false;
	}
	
	if(
		typeof(wayf_auto_login) == "undefined"
		|| typeof(wayf_auto_login) != "boolean"
		){
		wayf_auto_login = true;
	}
	
	if(
		typeof(wayf_hide_after_login) == "undefined"
		|| typeof(wayf_hide_after_login) != "boolean"
		){
		wayf_hide_after_login = false;
	}
	
	if(typeof(wayf_logged_in_messsage) == "undefined"){
		wayf_logged_in_messsage = "{$loggedInString}";
	}
	
	if(
		typeof(wayf_most_used_idps) == "undefined"
		|| typeof(wayf_most_used_idps) != "object"
		){
		wayf_most_used_idps = new Array();
	}
	
	if(
		typeof(wayf_show_categories) == "undefined"
		|| typeof(wayf_show_categories) != "boolean"
		){
		wayf_show_categories = true;
	}
	
	if(
		typeof(wayf_hide_categories) == "undefined"
		|| typeof(wayf_hide_categories) != "object"
		){
		wayf_hide_categories = new Array();
	}
	
	if(
		typeof(wayf_unhide_idps) == "undefined"
		||  typeof(wayf_unhide_idps) != "object"
	){
		wayf_unhide_idps = new Array();
	}
	
	// Disable categories if IdPs are unhidden from hidden categories
	if (wayf_unhide_idps.length > 0){
		wayf_show_categories = false;
	}
	
	if(
		typeof(wayf_hide_idps) == "undefined"
		|| typeof(wayf_hide_idps) != "object"
		){
		wayf_hide_idps = new Array();
	}
	
	if(
		typeof(wayf_additional_idps) == "undefined"
		|| typeof(wayf_additional_idps) != "object"
		){
		wayf_additional_idps = [];
	}
	
	// Exit without outputting html if config is not ok
	if (config_ok != true){
		return;
	}
	
	// Check if user is logged in already:
	var user_logged_in = false;
	if (typeof(wayf_check_login_state_function) == "undefined"
		|| typeof(wayf_check_login_state_function) != "function" ){
		// Use default Shibboleth Service Provider login check
		user_logged_in = isCookie('shibsession');
	} else {
		// Use custom function
		user_logged_in = wayf_check_login_state_function();
	}
	
	// Check if user is authenticated already and 
	// whether something has to be drawn
	if (
		wayf_hide_after_login 
		&& user_logged_in 
		&& wayf_logged_in_messsage == ''
	){
		
		// Exit script without drawing
		return;
	}
	
	// Now start generating the HTML for outer box
	if(
		wayf_hide_after_login 
		&& user_logged_in
	){
		writeHTML('<div id="wayf_div" style="background:' + wayf_background_color + ';border-style: solid;border-color: ' + wayf_border_color + ';border-width: 1px;padding: 10px;height: auto;width: ' + wayf_width + ';text-align: left;overflow: hidden;">');
	} else {
		writeHTML('<div id="wayf_div" style="background:' + wayf_background_color + ';border-style: solid;border-color: ' + wayf_border_color + ';border-width: 1px;padding: 10px;height: ' + wayf_height + ';width: ' + wayf_width + ';text-align: left;">');
	}

	
	// Shall we display the logo
	if (wayf_hide_logo != true){
		
		// Write header of logo div
		writeHTML('<div id="wayf_logo_div" style="float: right;margin-bottom: 5px;"><a href="$federationURL" target="_blank" style="border:0px">');
		
		// Which size of the logo shall we display
		if (wayf_use_small_logo){
			writeHTML('<img id="wayf_logo" src="{$smallLogoURL}" alt="Federation Logo" style="border:0px">')
		} else {
			writeHTML('<img id="wayf_logo" src="{$logoURL}" alt="Federation Logo" style="border:0px">')
		}
		
		// Write footer of logo div
		writeHTML('</a></div>');
	}
	
	// Start login check
	// Search for login state cookie
	// If one exists, we only draw the logged_in_message
	if(
		wayf_hide_after_login 
		&& user_logged_in
	){
		writeHTML('<p id="wayf_intro_div" style="float:left;font-size:' + wayf_font_size + 'px;color:' + wayf_font_color + ';">' + wayf_logged_in_messsage + '</p>');
		
	} else {
	// Else draw embedded WAYF
		
		//Do we have to draw custom text? or any text at all?
		if(typeof(wayf_overwrite_intro_text) == "undefined"){
			writeHTML('<label for="user_idp" id="wayf_intro_label" style="float:left; min-width:80px; font-size:' + wayf_font_size + 'px;color:' + wayf_font_color + ';margin-top: 5px;">{$loginWithString}');
		} else if (wayf_overwrite_intro_text != "") {
			writeHTML('<label for="user_idp" id="wayf_intro_label" style="float:left; min-width:80px; font-size:' + wayf_font_size + 'px;color:' + wayf_font_color + ';margin-top: 5px;">' + wayf_overwrite_intro_text);
		}
		
		// Get local cookie
		var saml_idp = getCookie('_saml_idp');
		var last_idp = '';
		var last_idps = new Array();
		
		if (saml_idp && saml_idp.length > 0){
			last_idps = saml_idp.split('+')
			if (last_idps[0] && last_idps[0].length > 0){
				last_idp = decodeBase64(last_idps[0]);
			}
		}

		if (last_idp == "" && safekind == 2) {
			writeHTML('<img src="{$alertURL}" title="{$alertspTooltip}" style="vertical-align:text-bottom; border:0px; width:20px; height:20px;">');
		}
		writeHTML('</label>');
		
		var wayf_authReq_URL = '';
		var form_start = '';
		
		if (wayf_use_discovery_service == true){
			var return_url = wayf_sp_samlDSURL + '?SAMLDS=1&target=' + encodeURIComponent(wayf_return_url);
			
			wayf_authReq_URL = wayf_URL 
			+ '?entityID=' + encodeURIComponent(wayf_sp_entityID)
			+ '&amp;return=' + encodeURIComponent(return_url);
			
			form_start = '<form id="IdPList" name="IdPList" method="post" target="_parent" onSubmit="return submitForm()" action="' + wayf_authReq_URL + '">';
		} else {
			
			wayf_authReq_URL = wayf_URL 
			+ '?providerId=' + encodeURIComponent(wayf_sp_entityID)
			+ '&amp;shire=' + encodeURIComponent(wayf_sp_samlACURL)
			+ '&amp;target=' + encodeURIComponent(wayf_return_url);
			
			form_start = '<form id="IdPList" name="IdPList" method="post" target="_parent" onSubmit="return submitForm()" action="' + wayf_authReq_URL + '&amp;time={$utcTime}'
			+ '">';
		}
		
SCRIPT;
	
	// Create redirect links in case the checkbox is checked
	if (isset($_COOKIE[$redirectCookieName]) && !empty($_COOKIE[$redirectCookieName])){
		// Redirect user to WAYF automatically
		echo <<<SCRIPT
		
		// Do auto login in case this option is set
		if (wayf_auto_login){
		
			// Redirect user automatically to WAYF
			var redirect_url = wayf_authReq_URL.replace(/&amp;/g, '&');
			
			// Make sure the redirect is always being done in the parent window
			if (window.parent){
				window.parent.location = redirect_url;
			} else {
				window.location = redirect_url;
			}
			
			// Return here and stop writing HTML
			return;
		}
		
SCRIPT;
		
	}
	
	echo <<<SCRIPT

		writeHTML('<link rel="stylesheet" href="{$incsearchCssURL}" type="text/css" />');
		writeHTML('<script type="text/javascript" src="{$ajaxLibURL}"></script>');
		writeHTML('<script type="text/javascript" src="{$ajaxFlickLibURL}"></script>');
		writeHTML('<script type="text/javascript" src="{$incsearchLibURL}"></script>');

		writeHTML(form_start);
		writeHTML('<input name="request_type" type="hidden" value="embedded">');
		writeHTML('<input id="user_idp" name="user_idp" type="hidden" value="">');

		// Favourites
		if (wayf_most_used_idps.length > 0){
			if(typeof(wayf_overwrite_most_used_idps_text) != "undefined"){
				favorite_idp_group = wayf_overwrite_most_used_idps_text;
			}

			// Show additional IdPs in the order they are defined
			for ( var i=0; i < wayf_most_used_idps.length; i++){
				if (wayf_idps[wayf_most_used_idps[i]]){
					favorite_list.push(wayf_idps[wayf_most_used_idps[i]].search.slice());
					favorite_list[favorite_list.length - 1][1] = favorite_idp_group;
				}
			}
		}

SCRIPT;


	// Generate drop-down list
	foreach ($IDProviders as $key => $IDProvider){
		
		// Get IdP Name
		if (isset($IDProvider[$language]['Name'])){
			$IdPName = addslashes($IDProvider[$language]['Name']);
		} else {
			$IdPName = addslashes($IDProvider['Name']);
		}

		// Figure out if entry is valid or a category
		if (!isset($IDProvider['SSO'])){
			continue;
		}
		
		// Set selected attribute
		echo <<<SCRIPT
		if (last_idp == '{$key}'){
			dispDefault = '{$IdPName}';
		}
SCRIPT;

		$IdPType = isset($IDProviders[$key]['Type']) ? $IDProviders[$key]['Type'] : '';

		echo <<<SCRIPT
		if (isAllowedType('{$key}','{$IdPType}') && isAllowedIdP('{$key}')){
			if (
				"{$selectedIDP}" == "-" 
				&& typeof(wayf_default_idp) != "undefined"
				&& wayf_default_idp == "{$key}"
				){
				dispDefault = '{$IdPName}';
			}
			pushIncSearchList('{$key}');
		}
SCRIPT;
	}
	
	echo <<<SCRIPT
		if (wayf_additional_idps.length > 0){
			var listcnt = inc_search_list.length;
			
			// Show additional IdPs in the order they are defined
			for ( var i=0; i < wayf_additional_idps.length ; i++){
				if (wayf_additional_idps[i]){
					// Last used IdP is known because of local _saml_idp cookie
					if (
						wayf_additional_idps[i].name
						&& wayf_additional_idps[i].entityID == last_idp
						){
						dispDefault = wayf_additional_idps[i].name;
						inc_search_list[listcnt] = new Array();
						inc_search_list[listcnt][0] = wayf_additional_idps[i].entityID;
                                                inc_search_list[listcnt][1] = "{$otherFederationString}";
						inc_search_list[listcnt][2] = wayf_additional_idps[i].name;
						inc_search_list[listcnt][3] = wayf_additional_idps[i].name;
						listcnt++;
					}
					// If no IdP is known but the default IdP matches, use this entry
					else if (
						wayf_additional_idps[i].name
						&& typeof(wayf_default_idp) != "undefined" 
						&& wayf_additional_idps[i].entityID == wayf_default_idp
						){
						dispDefault = wayf_additional_idps[i].name;
						inc_search_list[listcnt] = new Array();
						inc_search_list[listcnt][0] = wayf_additional_idps[i].entityID;
                                                inc_search_list[listcnt][1] = "{$otherFederationString}";
						inc_search_list[listcnt][2] = wayf_additional_idps[i].name;
						inc_search_list[listcnt][3] = wayf_additional_idps[i].name;
						listcnt++;
					} else if (wayf_additional_idps[i].name) {
						inc_search_list[listcnt] = new Array();
						inc_search_list[listcnt][0] = wayf_additional_idps[i].entityID;
                                                inc_search_list[listcnt][1] = "{$otherFederationString}";
						inc_search_list[listcnt][2] = wayf_additional_idps[i].name;
						inc_search_list[listcnt][3] = wayf_additional_idps[i].name;
						listcnt++;
					}
				}
			}
			
		}
		writeHTML('<div style="clear:both;"></div>');
		writeHTML('<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">');
		writeHTML('<tr>');
		writeHTML('<td id="keytext_td" style="width: 100%;">');
		if (dispDefault == ''){
			dispidp = initdisp;
		} else {
			dispidp = dispDefault;
		}
		writeHTML('<input id="keytext" type="text" name="pattern" value="" autocomplete="off" size="60" tabindex=5 style="float: left; width: 100%; display: block"/>');
		
		writeHTML('<div style="clear:both;"></div>');
		writeHTML('<div id="view_incsearch_base">');
		writeHTML('<div id="view_incsearch_animate" style="height:' + wayf_list_height + ';">');
		writeHTML('<div id="view_incsearch_scroll" style="height:' + wayf_list_height + ';">');
		writeHTML('<div id="view_incsearch"></div>');
		writeHTML('</div>');
		writeHTML('</div>');
		writeHTML('</div>');
		writeHTML('</td>');
		
		writeHTML('<td>');
		writeHTML('<img id="dropdown_img" src="{$dropdownDnURL}" title="{$dropdownTooltip}" tabindex=6 style="border:0px; width:20px; height:20px; vertical-align:middle;">');
		writeHTML('</td>');
		
		writeHTML('<td>');
		writeHTML('&nbsp;');
		writeHTML('</td>');
		
		writeHTML('<td>');
		// Do we have to display custom text?
		if(typeof(wayf_overwrite_submit_button_text) == "undefined"){
			writeHTML('<input id="wayf_submit_button" type="submit" name="Login" accesskey="s" value="{$loginString}" tabindex="10" ');
		} else {
			writeHTML('<input id="wayf_submit_button" type="submit" name="Login" accesskey="s" value="' + wayf_overwrite_submit_button_text + '" tabindex="10" ');
		}

		if (dispidp == initdisp) {
			writeHTML('disabled >');
		} else {
			writeHTML('>');
		}

		writeHTML('</td>');
		writeHTML('</tr>');

		
		writeHTML('<tr>');
		writeHTML('<td colspan="3">');
		// Draw checkbox
		writeHTML('<div id="wayf_remember_checkbox_div" style="float: left;margin-top: 0px;margin-bottom:0px; width: 100%;">');
		// Do we have to show the remember settings checkbox?
		if (wayf_show_remember_checkbox){
			// Is the checkbox forced to be checked
			if (wayf_force_remember_for_session){
				// First draw the dummy checkbox ...
				writeHTML('<input id="wayf_remember_checkbox" type="checkbox" name="session_dummy" value="true" tabindex=8 checked="checked" disabled="disabled" >&nbsp;');
				// ... and now the real but hidden checkbox
				writeHTML('<input type="hidden" name="session" value="true">&nbsp;');
			} else {
				writeHTML('<input id="wayf_remember_checkbox" type="checkbox" name="session" value="true" tabindex=8 {$checkedBool}>&nbsp;');
			}
			
			// Do we have to display custom text?
			if(typeof(wayf_overwrite_checkbox_label_text) == "undefined"){
				writeHTML('<label for="wayf_remember_checkbox" id="wayf_remember_checkbox_label" style="min-width:80px; font-size:' + wayf_font_size + 'px;color:' + wayf_font_color + ';">{$rememberSelectionText}</label>');
				
			} else if (wayf_overwrite_checkbox_label_text != "")  {
				writeHTML('<label for="wayf_remember_checkbox" id="wayf_remember_checkbox_label" style="min-width:80px; font-size:' + wayf_font_size + 'px;color:' + wayf_font_color + ';">' + wayf_overwrite_checkbox_label_text + '</label>');
			}
		} else if (wayf_force_remember_for_session){
			// Is the checkbox forced to be checked but hidden
			writeHTML('<input id="wayf_remember_checkbox" type="hidden" name="session" value="true">&nbsp;');
		}
		writeHTML('</div>');
		writeHTML('</td>');
		
		writeHTML('<td style="vertical-align:middle; text-align:center;">');
		writeHTML('<div id="clear_a" class="default" title="{$clearTooltip}" tabindex=11>{$clearString}</div>');
		writeHTML('</td>');
		writeHTML('</tr>');
		writeHTML('</table>');
	
		// Close form
		writeHTML('</form>');
		
	}  // End login check
	
	// Close box
	writeHTML('</div>');
	writeHTML('<div style="clear:both;"></div>');
	
	// Now output HTML all at once
	document.write(wayf_html);
})()

SCRIPT;
}

?>
