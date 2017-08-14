<?php // Copyright (c) 2012, SWITCH - Serving Swiss Universities

/******************************************************************************/
// Prints the JavaScript that renders the Embedded WAYF
function printEmbeddedWAYFScript_IncSearch(){

	global $langStrings, $language, $imageURL, $logoURL, $smallLogoURL, $federationURL;
	global $selectedIDP, $IDProviders, $redirectCookieName, $redirectStateCookieName, $federationName;
	global $cookieSecurity;
	global $safekind, $selIdP, $incsearchLibURL, $incsearchCssURL, $alertURL, $dropdownUpURL, $dropdownDnURL, $ajaxLibURL, $ajaxFlickLibURL;
	global $mduiHintIDPs, $useMduiHintMax, $geolocationOffURL, $geolocationOnURL;
	global $JSONIdPList, $JSONIncCategoryList, $JSONIncIdPList, $JSONIncIdPHintList, $IdPHintList, $selIdP, $InitDisp, $hintIDPString, $IDProvidersKind;
	global $googleMapLibURL, $geolocationJsURL, $geolocationCssURL, $commonJsURL;
	
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
	$mapString = addslashes(getLocalString('map_button'));
	$regString = addslashes(getLocalString('reg_button'));
	$alertspTooltip = addslashes(getLocalString('alertsp_tooltip'));
	$dropdownTooltip = addslashes(getLocalString('dropdown_tooltip'));
	$clearTooltip = addslashes(getLocalString('clear_tooltip'));
	$mapTooltip = addslashes(getLocalString('map_tooltip'));
	$regTooltip = addslashes(getLocalString('reg_tooltip'));
	$geolocationTooltip = addslashes(getLocalString('geolocation_tooltip'));
	$geolocationErr1 = addslashes(getLocalString('geolocation_err1'));
	$geolocationErr2 = addslashes(getLocalString('geolocation_err2'));
	$geolocationErr3 = addslashes(getLocalString('geolocation_err3'));
	$geolocationErr4 = addslashes(getLocalString('geolocation_err4'));
	$locationsFilter = addslashes(getLocalString('locations_filter'));
	$categoryFilter = addslashes(getLocalString('category_filter'));
	$closeString = addslashes(getLocalString('close_button'));
	$geolocationString = addslashes(getLocalString('geolocation_button'));
	$noHintMsg = addslashes(getLocalString('no_hint_msg'));
	$noGeolocationMsg = addslashes(getLocalString('no_geolocation_msg'));
	$nearIdPString = addslashes(getLocalString('near_idp'));
	
	getSearchIdPList();
	
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
var wayf_use_disco_feed;
var wayf_discofeed_url;
var wayf_html = "";
var wayf_idps = { {$JSONIdPList} };

var wayf_incidps = [{$JSONIncIdPList}];
var wayf_incidps_hint = [{$JSONIncIdPHintList}];

var json_category_list = {{$JSONIncCategoryList}};
var json_idp_list = new Array();
var json_idp_favoritelist = new Array();
var json_idp_hintlist = new Array();

var submit_check_list = new Array();
var safekind = '{$safekind}';
var allIdPList = '';
var initdisp = '{$InitDisp}';
var dispDefault = '{$selIdP}';
var dispidp = '';
var hiddenKeyText = '';
var dropdown_up = '{$dropdownUpURL}';
var dropdown_down = '{$dropdownDnURL}';
var geolocation_off = '{$geolocationOffURL}';
var geolocation_on = '{$geolocationOnURL}';
var hintmax = '{$useMduiHintMax}';
var favorite_idp_group = '{$mostUsedIdPsString}';
var hint_idp_group = '{$hintIDPString}';

var wayfdiv_id = 'wayfframe';
var reg_button = '{$regString}';
var geolocation_err1 = '{$geolocationErr1}';
var geolocation_err2 = '{$geolocationErr2}';
var geolocation_err3 = '{$geolocationErr3}';
var geolocation_err4 = '{$geolocationErr4}';
var close_button = '{$closeString}';
var geolocation_button = '{$geolocationString}';
var no_hint_msg = '{$noHintMsg}';
var no_geolocation_msg = '{$noGeolocationMsg}';
var near_idp = '{$nearIdPString}';

SCRIPT;

	printJscode_GlobalVariables();

	echo <<<SCRIPT

// It adds it to window event.
function start() {
	checkDiscofeed();
	suggest = new Suggest.Local(
		"keytext",							// element id of input area
		"view_incsearch",					// element id of IdP list display area
		"view_incsearch_animate",			// element id of IdP list display animate area
		"view_incsearch_scroll",			// element id of IdP list display scroll area
		json_idp_list,						// IdP list
		json_idp_favoritelist,				// IdP list (Favorite)
		json_idp_hintlist,					// IdP list (Hint IP, Domain)
		"dropdown_img",						// element id of dropdown image
		"geolocation_img",					// element id of geolocation image
		"wayf_submit_button",				// element id of select button
		"map_a",							// element id of map
		"clear_a",							// element id of clear
		initdisp,							// Initial display of input area
		dispDefault,						// Select IdP display of input area
		dropdown_down,						// URL of deropdown down image
		dropdown_up,						// URL of deropdown up image
		geolocation_off,					// URL of geolocation off image
		geolocation_on,						// URL of geolocation on image
		favorite_idp_group,					// favorite idp list group
		hint_idp_group,						// hint idp list group
		true,								// Embedded or Central Flg
		"optionElm",						// element id of option
		{
			dispMax: 500,					// option display IdP Max
			showgrp: wayf_show_categories	// option show category
		}
	);
}

window.addEventListener ?
	window.addEventListener('load', start, false) :
	window.attachEvent('onload', start);

// Define functions
function submitForm(){

	var NonFedEntityID;
	var idp_name = document.getElementById('keytext').value.toLowerCase();
	var chkFlg = false;
	if (hiddenKeyText != '') idp_name = hiddenKeyText.toLowerCase();

if (discofeed_flg){
	if (json_idp_list.length > 0) {
		submit_check_list = json_idp_list;
	}
	if (json_idp_favoritelist.length > 0) {
		submit_check_list = json_idp_favoritelist.concat(submit_check_list);
	}
	if (json_idp_hintlist.length > 0) {
		submit_check_list = json_idp_hintlist.concat(submit_check_list);
	}
}
	
	for (var i=0; i<submit_check_list.length; i++){
		for (var j = 0, len2 = submit_check_list[i].search.length; j < len2; j++) {
			var list_idp_name = submit_check_list[i].search[j].toLowerCase();
			if (idp_name == list_idp_name){
				NonFedEntityID = submit_check_list[i].entityid;
				document.getElementById('user_idp').value = submit_check_list[i].entityid;
				chkFlg = true;
				if (safekind > 0 && safekind != 3){
					// Store SAML domain cookie for this foreign IdP
					setCookie('_saml_idp', encodeBase64(submit_check_list[i].entityid) , 100);
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
        if (
                i >= (submit_check_list.length - wayf_additional_idps.length)){

                var redirect_url;

                // Store SAML domain cookie for this foreign IdP
                setCookie('_saml_idp', encodeBase64(NonFedEntityID) , 100);

                // Redirect user to SP handler
                if (wayf_use_discovery_service){
			
			var entityIDGETParam = getGETArgument("entityID");
			var returnGETParam = getGETArgument("return");
			if (entityIDGETParam != "" && returnGETParam != ""){
				redirect_url = returnGETParam;
			} else {
				redirect_url = wayf_sp_samlDSURL;
				redirect_url += getGETArgumentSeparator(redirect_url) + 'target=' + encodeURIComponent(wayf_return_url);
			}
			
			// Append selected Identity Provider
			redirect_url += '&entityID=' + encodeURIComponent(NonFedEntityID);
			
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
			setCookie('_saml_idp', encodeBase64(submit_check_list[i].entityid), -1);
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
	for (var i=0; i<wayf_incidps.length; i++){
		if (wayf_incidps[i].entityid == IdP) {
			json_idp_list.push(wayf_incidps[i]);
		}
	}
	for (var j=0; j<wayf_incidps_hint.length; j++){
		if (wayf_incidps_hint[j].entityid == IdP) {
			json_idp_hintlist.push(wayf_incidps_hint[j]);
		}
	}
}

function isAllowedType(IdP, type){
	for ( var i=0; i<wayf_hide_categories.length; i++){
		
		if (wayf_hide_categories[i] == type || wayf_hide_categories[i] == "all" ){
			
			for ( var i=0; i<wayf_unhide_idps.length; i++){
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
	
	for ( var i=0; i<wayf_hide_categories.length; i++){
		
		if (wayf_hide_categories[i] == category || wayf_hide_categories[i] == "all" ){
			return false;
		}
	}
	
	// Category was not hidden
	return true;
}

function isAllowedIdP(IdP){
	
	if (wayf_hide_idps[0] != "all"){
		for ( var i=0; i<wayf_hide_idps.length; i++){
			if (wayf_hide_idps[i] == IdP){
				return false;
			}
		}
		// IdP was not hidden
		return true;
	} else {
		for ( var i=0; i<wayf_unhide_idps.length; i++){
			if (wayf_unhide_idps[i] == IdP){
				return true;
			}
		}
		// IdP was hidden
		return false;
	}
}

function setCookie(c_name, value, expiredays){
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + expiredays);
	document.cookie=c_name + "=" + escape(value) +
	((expiredays==null) ? "" : "; expires=" + exdate.toGMTString()) +
	((wayf_sp_cookie_path=="") ? "" : "; path=" + wayf_sp_cookie_path)
SCRIPT;
	if( isset($cookieSecurity) )
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
	
	for ( var i = 0; i < a_all_cookies.length; i++ ){
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

// Query Shibboleth Session handler and process response afterwards
// This method has to be used because HttpOnly prevents reading 
// the shib session cookies via JavaScript
function isShibbolethSession(url){
	var xmlhttp;
	if (window.XMLHttpRequest){
		xmlhttp = new XMLHttpRequest();
	}  else {
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	// Send request
	try {
		xmlhttp.open("GET", url, false);
		xmlhttp.send();
	} catch (e) {
		// Something went wrong, send back false
		return false;
	} 
	
	// Check response code
	if (xmlhttp.readyState != 4 || xmlhttp.status != 200 ){
		return false;
	}
	
	// Return true if session handler shows valid session
	if (
		xmlhttp.responseText.search(/Authentication Time/i) > 0){
		return true;
	}
	
	return false;
}

// Sorts Discovery feed entries 
function sortEntities(a, b){
	var nameA = a.name.toLowerCase();
	var nameB = b.name.toLowerCase();
	
	if (nameA < nameB){
		return -1;
	}
	
	if (nameA > nameB){
		return 1;
	}
	
	return 0;
}

// Returns true if user is logged in
function isUserLoggedIn(){
	
	if (
		   typeof(wayf_check_login_state_function) != "undefined"
		&& typeof(wayf_check_login_state_function) == "function" ){
		
		// Use custom function
		return wayf_check_login_state_function();
	
	} else {
		// Check if Shibboleth session cookie exists
		var shibSessionCookieExists = isCookie('shibsession');
		
		// Check if Shibboleth session handler 
		var shibSessionHandlerShowsSession = isShibbolethSession(wayf_sp_handlerURL + '/Session');
		
		// Return true if one of these checks is succsesful
		return (shibSessionCookieExists || shibSessionHandlerShowsSession);
	}
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

function getGETArgument(name){
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexString = "[\\?&]"+name+"=([^&#]*)";
	var regex = new RegExp(regexString);
	var results = regex.exec(window.location.href);
	
	if( results == null ){
		return "";
	} else {
		return decodeURIComponent(results[1]);
	}
}

function getGETArgumentSeparator(url){
	if (url.indexOf('?') >=0 ){
		return '&';
	} else {
		return '?';
	}
}

(function() {
	
	var config_ok = true; 
	
	// Get GET parameters that maybe are set by Shibboleth
	var returnGETParam = getGETArgument("return");
	var entityIDGETParam = getGETArgument("entityID");
	
	// First lets make sure properties are available
	if(
		typeof(wayf_use_discovery_service)  == "undefined"  
		|| typeof(wayf_use_discovery_service) != "boolean"
	){
		wayf_use_discovery_service = true;
	}
	
	
	// Overwrite entityID with GET argument if present
	var entityIDGETParam = getGETArgument("entityID");
	if (entityIDGETParam != ""){
		wayf_sp_entityID = entityIDGETParam;
	}
	
	if(
		typeof(wayf_sp_entityID) == "undefined"
		|| typeof(wayf_sp_entityID) != "string"
		){
		alert('The mandatory parameter \'wayf_sp_entityID\' is missing. Please add it as a javascript variable on this page.');
		config_ok = false;
	}
	
	if(
		typeof(wayf_URL) == "undefined"
		|| typeof(wayf_URL) != "string"
		){
		alert('The mandatory parameter \'wayf_URL\' is missing. Please add it as a javascript variable on this page.');
		config_ok = false;
	}
	
	if(
		typeof(wayf_return_url) == "undefined"
		|| typeof(wayf_return_url) != "string"
		){
		alert('The mandatory parameter \'wayf_return_url\' is missing. Please add it as a javascript variable on this page.');
		config_ok = false;
	}

	if(
		typeof(wayf_sp_cookie_path)  == "undefined"  
		|| typeof(wayf_sp_cookie_path) != "string"
		){
		wayf_sp_cookie_path = '';
	}
	
	if(
		typeof(wayf_list_height) == "undefined" 
		|| typeof(wayf_list_height) != "number"
	){
		wayf_list_height = '150px';
	} else {
		wayf_list_height += 'px';
	}
	
	if(
		wayf_use_discovery_service == false 
		&& typeof(wayf_sp_handlerURL) == "undefined"
		){
		alert('The mandatory parameter \'wayf_sp_handlerURL\' is missing. Please add it as a javascript variable on this page.');
		config_ok = false;
	}
	
	if(
		wayf_use_discovery_service == true 
		&& typeof(wayf_sp_samlDSURL) == "undefined"
		){
		// Set to default DS handler
		wayf_sp_samlDSURL = wayf_sp_handlerURL + "/DS";
	}
	
	if (
		typeof(wayf_sp_samlACURL) == "undefined"
		|| typeof(wayf_sp_samlACURL) != "string"
		){
		wayf_sp_samlACURL = wayf_sp_handlerURL + '/SAML/POST';
	}
	
	if(
		typeof(wayf_font_color) == "undefined"
		|| typeof(wayf_font_color) != "string"
		){
		wayf_font_color = 'black';
	}
	
	if(
		typeof(wayf_font_size) == "undefined"
		|| typeof(wayf_font_size) != "number"
		){
		wayf_font_size = 12;
	}
	
	if(
		typeof(wayf_border_color) == "undefined"
		|| typeof(wayf_border_color) != "string"
		){
		wayf_border_color = '#848484';
	}
	
	if(
		typeof(wayf_background_color) == "undefined"
		|| typeof(wayf_background_color) != "string"
		){
		wayf_background_color = '#F0F0F0';
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
	
	if(
		typeof(wayf_width) == "undefined" 
		|| typeof(wayf_width) != "number"
	){
		wayf_width = "auto";
	} else {
		wayf_width += 'px';
	}
	
	if(
		typeof(wayf_height) == "undefined" 
		|| typeof(wayf_height) != "number"
		){
		wayf_height = "auto";
	} else {
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
	
	if(
		typeof(wayf_logged_in_messsage) == "undefined"
		|| typeof(wayf_logged_in_messsage) != "string"
		){
		wayf_logged_in_messsage = "{$loggedInString}".replace(/%s/, wayf_return_url);
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
	
	if(
		typeof(wayf_use_disco_feed) == "undefined"
		|| typeof(wayf_use_disco_feed) != "boolean"
		){
		if (
			wayf_background_color == '#F4F7F7'
			&& typeof(wayf_discofeed_url) != "undefined"
			&& typeof(wayf_discofeed_url) == "string"
			&& wayf_discofeed_url != ""
			){
			wayf_use_disco_feed = true;
		} else {
			wayf_use_disco_feed = false;
		}
	}
	
	if(
		typeof(wayf_googlemap_key) == "undefined"
		|| typeof(wayf_googlemap_key) != "string"
		){
		wayf_googlemap_key = '';
	}
	
	if(
		typeof(wayf_discofeed_url) == "undefined"
		|| typeof(wayf_discofeed_url) != "string"
		){
		wayf_discofeed_url = "/Shibboleth.sso/DiscoFeed";
	}
	
	// Exit without outputting html if config is not ok
	if (config_ok != true){
		return;
	}
	
	// Check if user is logged in already:
	var user_logged_in = isUserLoggedIn();
	
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
	
	writeHTML('<div id="wayf_div">');
	writeHTML('<div id="wayf_mapframe" style="display:none;">');
	writeHTML('	<div id="mapleft" class="wayf_mframe"></div>');
	writeHTML('	<div id="mapcenter" class="wayf_mframe"></div>');
	writeHTML('	<div id="mapright"></div>');
	writeHTML('</div>');
	
	// Now start generating the HTML for outer box
	if(
		wayf_hide_after_login 
		&& user_logged_in
	){
		writeHTML('<div id="wayfframe" style="background:' + wayf_background_color + ';border-style: solid;border-color: ' + wayf_border_color + ';border-width: 1px;padding: 10px;height: auto;width: ' + wayf_width + ';text-align: left;overflow: hidden;">');
	} else {
		writeHTML('<div id="wayfframe" style="background:' + wayf_background_color + ';border-style: solid;border-color: ' + wayf_border_color + ';border-width: 1px;padding: 10px;height: ' + wayf_height + ';width: ' + wayf_width + ';text-align: left;">');
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
			// New SAML Discovery Service protocol
			
			wayf_authReq_URL = wayf_URL 
			
			// Use GET arguments or use configuration parameters
			if (entityIDGETParam != "" && returnGETParam != ""){
				wayf_authReq_URL += '?entityID=' + encodeURIComponent(entityIDGETParam);
				wayf_authReq_URL += '&amp;return=' + encodeURIComponent(returnGETParam);
			} else {
				var return_url = wayf_sp_samlDSURL + getGETArgumentSeparator(wayf_sp_samlDSURL);
				return_url += 'SAMLDS=1&target=' + encodeURIComponent(wayf_return_url);
				wayf_authReq_URL += '?entityID=' + encodeURIComponent(wayf_sp_entityID);
				wayf_authReq_URL += '&amp;return=' + encodeURIComponent(return_url);
			}
		} else {
			// Old Shibboleth WAYF protocol
			wayf_authReq_URL = wayf_URL;
			wayf_authReq_URL += '?providerId=' + encodeURIComponent(wayf_sp_entityID);
			wayf_authReq_URL += '&amp;target=' + encodeURIComponent(wayf_return_url);
			wayf_authReq_URL += '&amp;shire=' + encodeURIComponent(wayf_sp_samlACURL);
			wayf_authReq_URL += '&amp;time={$utcTime}';
		}
		
		// Add form element
		form_start = '<form id="IdPList" name="IdPList" method="post" target="_parent" action="' + wayf_authReq_URL + '">';
		
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
		writeHTML('<link rel="stylesheet" href="{$geolocationCssURL}" type="text/css" />');
		writeHTML('<script type="text/javascript" src="{$ajaxLibURL}"></script>');
		writeHTML('<script type="text/javascript" src="{$ajaxFlickLibURL}"></script>');
		writeHTML('<script type="text/javascript" src="{$googleMapLibURL}&key=' + wayf_googlemap_key + '"></script>');
		writeHTML('<script type="text/javascript" src="{$geolocationJsURL}"></script>');
		writeHTML('<script type="text/javascript" src="{$commonJsURL}"></script>');
		writeHTML('<script type="text/javascript" src="{$incsearchLibURL}"></script>');
		writeHTML('<script language="JavaScript" type="text/javascript">');
		writeHTML('	//$(function(){checkDiscofeed();});');
		writeHTML('</script>');

		writeHTML(form_start);
		writeHTML('<div id="optionElm" style="display:none;"></div>');
		writeHTML('<input name="request_type" type="hidden" value="embedded">');

		// Favourites
		if (wayf_most_used_idps.length > 0){
			if(typeof(wayf_overwrite_most_used_idps_text) != "undefined"){
				favorite_idp_group = wayf_overwrite_most_used_idps_text;
			}

			// Show additional IdPs in the order they are defined
			for ( var i=0; i < wayf_most_used_idps.length; i++){
				if (wayf_idps[wayf_most_used_idps[i]]){
					json_idp_favoritelist.push(json_idp_list[wayf_most_used_idps[i]]);
					json_idp_favoritelist[json_idp_favoritelist.length - 1].categoryName = favorite_idp_group;
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
			var listcnt = json_idp_list.length;
			
			// Sort Array
			wayf_additional_idps.sort(sortEntities)
			
			// Show additional IdPs
			for ( var i=0; i < wayf_additional_idps.length ; i++){
				if (wayf_additional_idps[i]){
					// Last used IdP is known because of local _saml_idp cookie
					if (
						wayf_additional_idps[i].name
						&& wayf_additional_idps[i].entityID == last_idp
						){
						dispDefault = wayf_additional_idps[i].name;
						json_idp_list[listcnt] = new Array();
						json_idp_list[listcnt].entityid = wayf_additional_idps[i].entityID;
                                                json_idp_list[listcnt].categoryName = "{$otherFederationString}";
						json_idp_list[listcnt].name = wayf_additional_idps[i].name;
						if (wayf_additional_idps[i].LogoURL){
							json_idp_list[listcnt].logoURL = wayf_additional_idps[i].LogoURL;
						} else {
							json_idp_list[listcnt].logoURL = '';
						}
						json_idp_list[listcnt].geolocation = new Array();
						if (wayf_additional_idps[i].GeolocationHint){
							json_idp_list[listcnt].geolocation = wayf_additional_idps[i].GeolocationHint;
						}
						if (wayf_additional_idps[i].RegistrationURL){
							json_idp_list[listcnt].registrationURL = wayf_additional_idps[i].RegistrationURL;
						} else {
							json_idp_list[listcnt].registrationURL = '';
						}
						if (wayf_additional_idps[i].kind){
							json_idp_list[listcnt].kind = wayf_additional_idps[i].kind;
						} else {
							json_idp_list[listcnt].kind = new Array();
							json_idp_list[listcnt].kind[0] = 'category:organizationType:others';
						}
						json_idp_list[listcnt].search = new Array();
						json_idp_list[listcnt].search[0] = wayf_additional_idps[i].name;
						listcnt++;
					}
					// If no IdP is known but the default IdP matches, use this entry
					else if (
						wayf_additional_idps[i].name
						&& typeof(wayf_default_idp) != "undefined" 
						&& wayf_additional_idps[i].entityID == wayf_default_idp
						){
						dispDefault = wayf_additional_idps[i].name;
						json_idp_list[listcnt] = new Array();
						json_idp_list[listcnt].entityid = wayf_additional_idps[i].entityID;
                                                json_idp_list[listcnt].categoryName = "{$otherFederationString}";
						json_idp_list[listcnt].name = wayf_additional_idps[i].name;
						if (wayf_additional_idps[i].LogoURL){
							json_idp_list[listcnt].logoURL = wayf_additional_idps[i].LogoURL;
						} else {
							json_idp_list[listcnt].logoURL = '';
						}
						json_idp_list[listcnt].geolocation = new Array();
						if (wayf_additional_idps[i].GeolocationHint){
							json_idp_list[listcnt].geolocation = wayf_additional_idps[i].GeolocationHint;
						}
						if (wayf_additional_idps[i].RegistrationURL){
							json_idp_list[listcnt].registrationURL = wayf_additional_idps[i].RegistrationURL;
						} else {
							json_idp_list[listcnt].registrationURL = '';
						}
						if (wayf_additional_idps[i].kind){
							json_idp_list[listcnt].kind = wayf_additional_idps[i].kind;
						} else {
							json_idp_list[listcnt].kind = new Array();
							json_idp_list[listcnt].kind[0] = 'category:organizationType:others';
						}
						json_idp_list[listcnt].search = new Array();
						json_idp_list[listcnt].search[0] = wayf_additional_idps[i].name;
						listcnt++;
					} else if (wayf_additional_idps[i].name) {
						json_idp_list[listcnt] = new Array();
						json_idp_list[listcnt].entityid = wayf_additional_idps[i].entityID;
                                                json_idp_list[listcnt].categoryName = "{$otherFederationString}";
						json_idp_list[listcnt].name = wayf_additional_idps[i].name;
						if (wayf_additional_idps[i].LogoURL){
							json_idp_list[listcnt].logoURL = wayf_additional_idps[i].LogoURL;
						} else {
							json_idp_list[listcnt].logoURL = '';
						}
						json_idp_list[listcnt].geolocation = new Array();
						if (wayf_additional_idps[i].GeolocationHint){
							json_idp_list[listcnt].geolocation = wayf_additional_idps[i].GeolocationHint;
						}
						if (wayf_additional_idps[i].RegistrationURL){
							json_idp_list[listcnt].registrationURL = wayf_additional_idps[i].RegistrationURL;
						} else {
							json_idp_list[listcnt].registrationURL = '';
						}
						if (wayf_additional_idps[i].kind){
							json_idp_list[listcnt].kind = wayf_additional_idps[i].kind;
						} else {
							json_idp_list[listcnt].kind = new Array();
							json_idp_list[listcnt].kind[0] = 'category:organizationType:others';
						}
						json_idp_list[listcnt].search = new Array();
						json_idp_list[listcnt].search[0] = wayf_additional_idps[i].name;
						listcnt++;
					}
				}
			}
			
		}
		if (dispDefault == ''){
			dispidp = initdisp;
		} else {
			dispidp = dispDefault;
		}
		writeHTML('<div class="wayf_userInputArea">');
		writeHTML('<div class="wayf_col">');
		writeHTML('<div class="wayf_col">');
		writeHTML('<div class="wayf_radioArea">');
		writeHTML('<div class="wayf_row">');
		writeHTML('<div class="wayf_optionTitle" style="width:6em;">');
		writeHTML('{$locationsFilter}');
		writeHTML('</div>');
		writeHTML('<div class="wayf_optionRadio">');
SCRIPT;
	
	$ua=$_SERVER['HTTP_USER_AGENT'];
	$browser=((strpos($ua,'iPhone')!==false)||(strpos($ua,'iPod')!==false)||(strpos($ua,'Android')!==false));
	if($browser=='sp') {
		$deviceType = 'mobile';
	} else {
		$deviceType = 'other';
  	}
	$tabindex = 8;
	$idindex = 0;
	if ($deviceType == 'mobile'){
	echo <<<SCRIPT
		writeHTML('                  <select name=\"locationgroup\" onchange=\"changeLocation_sel();\">');
SCRIPT;
	}
	
	foreach ($IDProviders as $key => $IDProviderLocation){
		$IdPType = isset($IDProviders[$key]['Type']) ? $IDProviders[$key]['Type'] : '';
		if ($IdPType != 'category'){ continue; }
		if (isset($IDProviderLocation[$language]['Name'])){
			$IdPLocationName = addslashes($IDProviderLocation[$language]['Name']);
		} else {
			$IdPLocationName = addslashes($IDProviderLocation['Name']);
		}
		if (isset($IDProviderLocation['Default'])){
			$IdPLocationChecked = $IDProviderLocation['Default'];
		}
		$idindex++;
		if ($deviceType != 'mobile'){
	echo <<<SCRIPT
		writeHTML('                  <div class=\"wayf_row\">');
		writeHTML('                  <input type=\"radio\" id=\"location$idindex\" tabindex=$tabindex name=\"locationgroup\" value=\"$key\" onclick=\"changeLocation();\" $IdPLocationChecked/>');
		writeHTML('                  <label for=\"location$idindex\" class=\"wayf_label_option\">$IdPLocationName</label>');
		writeHTML('                  </div>');
SCRIPT;
			$tabindex++;
		} else {
	echo <<<SCRIPT
		writeHTML('                  <option id=\"location$idindex\" name=\"locationgroup\" value=\"$key\" $IdPLocationChecked>$IdPLocationName</option>');
SCRIPT;
		}
	}
	if ($deviceType == 'mobile'){
	echo <<<SCRIPT
		writeHTML('                  </select>');
SCRIPT;
		$tabindex++;
	}
	echo <<<SCRIPT
		writeHTML('</div>');
		writeHTML('</div>');
		writeHTML('<div class="wayf_row">');
		writeHTML('<div class="wayf_optionTitle" style="width:6em;">');
		writeHTML('{$categoryFilter}');
		writeHTML('</div>');
		writeHTML('<div class="wayf_optionRadio">');
SCRIPT;

	$idindex = 0;
	if ($deviceType == 'mobile'){
	echo <<<SCRIPT
		writeHTML('                  <select name=\"kindgroup\" onchange=\"changeKind_sel();\">');
SCRIPT;
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
	echo <<<SCRIPT
			writeHTML('                  <div class=\"wayf_row\">');
			writeHTML('                  <input type=\"radio\" id=\"kind$idindex\" tabindex=$tabindex name=\"kindgroup\" value=\"$key\" onclick=\"changeKind();\" $IdPKindChecked/>');
			writeHTML('                  <label for=\"kind$idindex\" class=\"wayf_label_option\">$IdPKindName</label>');
			writeHTML('                  </div>');
SCRIPT;
			$tabindex++;
		} else {
	echo <<<SCRIPT
			writeHTML('                  <option id=\"kind$idindex\" name=\"kindgroup\" value=\"$key\" $IdPKindChecked/>$IdPKindName</option>');
SCRIPT;
		}
	}
	if ($deviceType == 'mobile'){
	echo <<<SCRIPT
		writeHTML('                  </select>');
SCRIPT;
		$tabindex++;
	}

	echo <<<SCRIPT
		writeHTML('</div>');
		writeHTML('</div>');
		writeHTML('</div>');
		writeHTML('<div class="wayf_inputArea">');
		writeHTML('<div class="wayf_inputtext">');
		writeHTML('<input id="user_idp" name="user_idp" type="hidden" value="">');
		writeHTML('<input id="keytext" type="text" name="pattern" value="" autocomplete="off" size="60" tabindex=5 style="width: 100%; display: block"/>');
		writeHTML('<div id="view_incsearch_base">');
		writeHTML('<div id="view_incsearch_animate" style="height:' + wayf_list_height + ';">');
		writeHTML('<div id="view_incsearch_scroll" style="height:' + wayf_list_height + ';">');
		writeHTML('<div id="view_incsearch"></div>');
		writeHTML('</div>');
		writeHTML('</div>');
		writeHTML('</div>');
		writeHTML('</div>');

		writeHTML('<div class="wayf_eventItem">');
		writeHTML('<img id="dropdown_img" src="{$dropdownDnURL}" title="{$dropdownTooltip}" tabindex=6 style="border:0px; width:20px; height:20px; vertical-align:middle;">');
		writeHTML('</div>');
		
		writeHTML('<div class="wayf_eventItem">');
		writeHTML('<img id="geolocation_img" src="{$geolocationOffURL}" title="{$geolocationTooltip}" tabindex=7 style="border:0px; width:20px; height:20px; vertical-align:middle;">');
		writeHTML('</div>');
		
		writeHTML('<div id="wayf_submit_div" class="wayf_eventItem">');
		// Do we have to display custom text?
		if(typeof(wayf_overwrite_submit_button_text) == "undefined"){
			writeHTML('<input id="wayf_submit_button" type="submit" name="Login" accesskey="s" value="{$loginString}" tabindex="19" onClick="javascript:return submitForm();" ');
		} else {
			writeHTML('<input id="wayf_submit_button" type="submit" name="Login" accesskey="s" value="' + wayf_overwrite_submit_button_text + '" tabindex="19" onClick="javascript:return submitForm();" ');
		}
		if (dispidp == initdisp) {
			writeHTML('disabled >');
		} else {
			writeHTML('>');
		}
		writeHTML('</div>');
		writeHTML('</div>');
		writeHTML('<div class="wayf_row">');
		writeHTML('<div class="wayf_checkArea">');
		
		writeHTML('<div class="wayf_optionCheck">');
		// Do we have to show the remember settings checkbox?
		if (wayf_show_remember_checkbox){
			writeHTML('<div class="wayf_row">');
			// Is the checkbox forced to be checked
			if (wayf_force_remember_for_session){
				// First draw the dummy checkbox ...
				writeHTML('<input id="wayf_remember_checkbox" type="checkbox" name="session_dummy" value="true" tabindex=17 checked="checked" disabled="disabled" >&nbsp;');
				// ... and now the real but hidden checkbox
				writeHTML('<input type="hidden" name="session" value="true">&nbsp;');
			} else {
				writeHTML('<input id="wayf_remember_checkbox" type="checkbox" name="session" value="true" tabindex=17 {$checkedBool}>&nbsp;');
			}
			writeHTML('</div>');
			writeHTML('<div class="wayf_row">');
			// Do we have to display custom text?
			if(typeof(wayf_overwrite_checkbox_label_text) == "undefined"){
				writeHTML('<label for="wayf_remember_checkbox" id="wayf_remember_checkbox_label" style="min-width:80px; font-size:' + wayf_font_size + 'px;color:' + wayf_font_color + ';">{$rememberSelectionText}</label>');
				
			} else if (wayf_overwrite_checkbox_label_text != "")  {
				writeHTML('<label for="wayf_remember_checkbox" id="wayf_remember_checkbox_label" style="min-width:80px; font-size:' + wayf_font_size + 'px;color:' + wayf_font_color + ';">' + wayf_overwrite_checkbox_label_text + '</label>');
			}
			writeHTML('</div>');
		} else if (wayf_force_remember_for_session){
			writeHTML('<div class="wayf_row">');
			// Is the checkbox forced to be checked but hidden
			writeHTML('<input id="wayf_remember_checkbox" type="hidden" name="session" value="true">&nbsp;');
			writeHTML('</div>');
			writeHTML('<div class="wayf_row">');
			writeHTML('</div>');
		}
		writeHTML('</div>');
		writeHTML('</div>');
		writeHTML('<div class="wayf_linkArea">');
		writeHTML('<div class="wayf_col">');
		if (wayf_googlemap_key != ''){
			writeHTML('<a href="javascript:void(0)" id="map_a" title="{$mapTooltip}" tabindex=15>{$mapString}</a>');
		} else {
			writeHTML('<div id="map_a"></div>');
		}
		writeHTML('</div>');
		writeHTML('<div class="wayf_col">');
		writeHTML('<a href="javascript:void(0)" id="clear_a" title="{$clearTooltip}" tabindex=16>{$clearString}</a>');
		writeHTML('</div>');
		writeHTML('</div>');
		writeHTML('</div>');
		writeHTML('</div>');
		writeHTML('</div>');
		writeHTML('</div>');
		// Close form
		writeHTML('</form>');
		
	}  // End login check
	
	// Close box
	writeHTML('</div>');
	writeHTML('<div style="clear:both;"></div>');
	writeHTML('</div>');

	// Now output HTML all at once
	document.write(wayf_html);
})()

SCRIPT;
}

?>
