<?php

/******************************************************************************/
// Parses the reverse dns lookup hostname out of a string and returns domain
function getDomainNameFromURIHint_IncSearch($HintKeyList){
	
	global $IDProviders, $useMduiHintMax;
	
	$clientHostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	if ($clientHostname == $_SERVER['REMOTE_ADDR']){
		return $HintKeyList;
	}
	
	// Get domain name from client host name
	$clientDomainName = getDomainNameFromURI($clientHostname);
	if ($clientDomainName == ''){
		return $HintKeyList;
	}
	
	// Return first matching IdP entityID that contains the client domain name
	foreach ($IDProviders as $key => $idp){
		if (is_array($idp) && array_key_exists("DomainHint", $idp)) {
			foreach( $idp["DomainHint"] as $domainhint ) {
				if (count($HintKeyList) == $useMduiHintMax) {
					return $HintKeyList;
				}
				if (preg_match('/'.$domainhint.'$/', $clientHostname)){
					if (!checkHintIDP($key, $HintKeyList)) {
						array_push($HintKeyList, $key);
					}
				}
			}
		}
	}
	
	// No matching entityID was found
	return $HintKeyList;

}

/******************************************************************************/
// Determines the IdP according to the IP address if possible
function getIPAdressHint_IncSearch($HintKeyList) {

	global $IDProviders, $useMduiHintMax;

	foreach($IDProviders as $key => $idp) {
		if (is_array($idp) && array_key_exists("IPHint", $idp)) {
			$clientIP = $_SERVER["REMOTE_ADDR"];

			foreach( $idp["IPHint"] as $network ) {
				if (count($HintKeyList) == $useMduiHintMax) {
					return $HintKeyList;
				}
				if (isIPinCIDRBlock($network, $clientIP)) {
					if (!checkHintIDP($key, $HintKeyList)) {
						array_push($HintKeyList, $key);
					}
				}
			}
		}
	}
	return $HintKeyList;
}

function checkHintIDP($HintKey, $HintKeyList) {

	foreach($HintKeyList as $key) {
		if ($HintKey == $key) {
			return true;
		}
	}
	return false;

}


function getSearchIdPList() {
	
	global $IDProviders, $langStrings, $language, $selectedIDP, $mduiHintIDPs;
	global $IncSearchList, $IncSearchHintList, $JSONIdPList, $IdPHintList;
	global $selIdP, $InitDisp, $hintIDPString, $IDProvidersKind;
	
	$IncSearchArray = array();
	$IncSearchHintArray = array();
	$JSONIdPArray = array();

	$IdPHintList = '';
	$selIdP = '';
	$InitDisp = addslashes(getLocalString('select_idp'));
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
		
		// Set IdP Category
		$IdPCategory = '';
		$IDProviders2 = $IDProviders;
		foreach ($IDProviders2 as $key2 => $IDProvider2){
			$IdPType2 = isset($IDProviders2[$key2]['Type']) ? $IDProviders2[$key2]['Type'] : '';
			// Category
			if ($IdPType2 == 'category' && $IdPType == $key2){
				// Get IdP Category Name
				if (isset($IDProvider2[$language]['Name'])){
					$IdPCategory = addslashes($IDProvider2[$language]['Name']);
				} else {
					$IdPCategory = addslashes($IDProvider2['Name']);
				}
				if (!empty($IdPKind)){
					break;
				}
			}
		}
		
		// Set IdP Kind
		if (isset($IDProvider['AttributeValue'])){
			$IdPKindCheckFlg = false;
			$IdPKind = addslashes($IDProvider['AttributeValue']);
			foreach ($IDProvidersKind as $key3 => $IDProviderKind){
				if ($IdPKind == $key3){
					$IdPKindCheckFlg = true;
					break;
				}
			}
			if (!$IdPKindCheckFlg){
				$IdPKind = 'others';
			}
		} else {
			$IdPKind = 'others';
		}
		
		// Get IdP Logo URL and Size
		$IdPLogoURL = '';
		$IdPLogoHeight = '';
		$IdPLogoWidth = '';
		if (isset($IDProvider[$language]['Logo'])){
			$IdPLogoURL = $IDProvider[$language]['Logo']['url'];
			$IdPLogoHeight = $IDProvider[$language]['Logo']['height'];
			$IdPLogoWidth = $IDProvider[$language]['Logo']['width'];
		} elseif (isset($IDProvider['Logo'])) {
			$IdPLogoURL = $IDProvider['Logo']['url'];
			$IdPLogoHeight = $IDProvider['Logo']['height'];
			$IdPLogoWidth = $IDProvider['Logo']['width'];
		}
		
		// Get GeolocationHint latitude and longitude
		$IdPGeolocationHint = '';
		if (isset($IDProvider['GeolocationHint'])){
			foreach($IDProvider['GeolocationHint'] as $geolocation){
				if (empty($IdPGeolocationHint)){
					$IdPGeolocationHint = $geolocation;
				} else {
					$IdPGeolocationHint = $IdPGeolocationHint.';'.$geolocation;
				}
			}
		}
		
		// Get Registration URL
		$IdPRegistrationURL = isset($IDProvider['RegistrationURL']) ? $IDProvider['RegistrationURL'] : '';
		
		// Get Search IdP Name
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
		
		$IncSearchAdd = <<<ENTRY
, "{$IdPLogoURL}", "{$IdPLogoHeight}", "{$IdPLogoWidth}", "{$IdPGeolocationHint}", "{$IdPRegistrationURL}", "{$IdPKind}", "", {$SearchIdPName}
ENTRY;

		$IncSearchIDP = <<<ENTRY
"{$key}", "{$IdPCategory}", "{$IdPName}"{$IncSearchAdd}
ENTRY;
		
		$IncSearchArray[] = <<<ENTRY

	[
		{$IncSearchIDP}
	]
ENTRY;

		foreach ($mduiHintIDPs as $hintIDP) {
			if ($key == $hintIDP) {
				$IncSearchHintArray[] = <<<ENTRY

	[
		"{$key}", "{$hintIDPString}", "{$IdPName}"{$IncSearchAdd}
	]
ENTRY;
				if (empty($IdPHintList)) {
					$IdPHintList = '"'.$hintIDP.'"';
				} else {
					$IdPHintList = $IdPHintList.', "'.$hintIDP.'"';
				}
			}
		}
		
		$JSONIdPArray[] = <<<ENTRY

	"{$key}":{
		type:"{$IdPType}",
		name:"{$IdPName}",
		search:[{$IncSearchIDP}],
		SAML1SSOurl:"{$IdPSSO}"
		}
ENTRY;

	}
	
	$IncSearchList = join(',', $IncSearchArray);
	$IncSearchHintList = join(',', $IncSearchHintArray);
	$JSONIdPList = join(',', $JSONIdPArray);
	$selIdP = ($selIdP == '') ? $InitDisp : $selIdP ;
	
}


?>
