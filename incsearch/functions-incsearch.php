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
		if (is_array($idp) && array_key_exists("DomainHint", $idp)){
			foreach( $idp["DomainHint"] as $domainhint ){
				if (count($HintKeyList) == $useMduiHintMax){
					return $HintKeyList;
				}
				if ((!empty($domainhint)) && (preg_match('/'.$domainhint.'$/', $clientHostname))){
					if (!checkHintIdP($key, $HintKeyList)){
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
function getIPAdressHint_IncSearch($HintKeyList){
	
	global $IDProviders, $useMduiHintMax;
	
	foreach($IDProviders as $key => $idp){
		if (is_array($idp) && array_key_exists("IPHint", $idp)){
			$clientIP = $_SERVER["REMOTE_ADDR"];

			foreach( $idp["IPHint"] as $network ){
				if (count($HintKeyList) == $useMduiHintMax){
					return $HintKeyList;
				}
				if (isIPinCIDRBlock($network, $clientIP)){
					if (!checkHintIdP($key, $HintKeyList)){
						array_push($HintKeyList, $key);
					}
				}
			}
		}
	}
	return $HintKeyList;
}

function checkHintIdP($entityID, $IdPList){
	
	foreach($IdPList as $key){
		if ($entityID == $key){
			return true;
		}
	}
	return false;
	
}

function getSearchIdPList() {
	
	global $IDProviders, $langStrings, $language, $selectedIDP, $mduiHintIDPs;
	global $JSONIdPList, $JSONIncCategoryList, $JSONIncIdPList, $JSONIncIdPHintList, $IdPHintList;
	global $selIdP, $InitDisp, $hintIDPString, $IDProvidersKind;
	
	$IncSearchArray = array();
	$IncSearchHintArray = array();
	$JSONCategoryArray = array();
	$JSONIdPArray = array();
	$JSONIncIdPArray = array();
	$JSONIncIdPHintArray = array();
	
	$IdPHintList = '';
	$selIdP = '';
	$InitDisp = addslashes(getLocalString('select_idp'));
	$hintIDPString = addslashes(getLocalString('hint_idp'));
	
	$idpIndex = 0;
	$idphintIndex = 0;
	
	foreach ($IDProviders as $key => $IDProvider){
		
		// Get IdP Type(Category key)
		$IdPType = isset($IDProviders[$key]['Type']) ? $IDProviders[$key]['Type'] : '';
		
		// Skip non-IdP & non-Category entries
		if ($IdPType == ''){
			continue;
		}
		
		$CategoryName = '';
		$CategoryGeolocation = '';
		$CategoryMapscale = '';
		
		// Get Category entries
		if ($IdPType == 'category'){
			if (isset($IDProvider[$language]['Name'])){
				$CategoryName = addslashes($IDProvider[$language]['Name']);
			} else {
				$CategoryName = addslashes($IDProvider['Name']);
			}
			$CategoryGeolocation = isset($IDProvider['Geolocation']) ? addslashes($IDProvider['Geolocation']) : '';
			$CategoryMapscale = isset($IDProvider['Mapscale']) ? addslashes($IDProvider['Mapscale']) : 5;
			$JSONIncCategoryArray[] = <<<ENTRY

	"{$key}":{
		name:"{$CategoryName}",
		geolocation:"{$CategoryGeolocation}",
		mapscale:{$CategoryMapscale}
		}
ENTRY;
			
			continue;
		}
		
		// Get IdP Name
		if (isset($IDProvider[$language]['Name'])){
			$IdPName = addslashes($IDProvider[$language]['Name']);
		} else {
			$IdPName = addslashes($IDProvider['Name']);
		}
		
		// Get Select IdP Name
		if ($selIdP == ''){
			$selIdP = ($selectedIDP == $key) ? $IdPName : '' ;
		}
		
		// SSO
		if (isset($IDProvider['SSO'])){
			$IdPSSO = $IDProvider['SSO'];
		} else {
			$IdPSSO = '';
		}
		
		// Set IdP Category
		$IdPCategoryName = '';
		$IdPCategoryKey = '';
		$IDProviders2 = $IDProviders;
		foreach ($IDProviders2 as $key2 => $IDProvider2){
			$IdPType2 = isset($IDProviders2[$key2]['Type']) ? $IDProviders2[$key2]['Type'] : '';
			// Category
			if ($IdPType2 == 'category' && $IdPType == $key2){
				// Get IdP Category Name
				if (isset($IDProvider2[$language]['Name'])){
					$IdPCategoryName = addslashes($IDProvider2[$language]['Name']);
				} else {
					$IdPCategoryName = addslashes($IDProvider2['Name']);
				}
				$IdPCategoryKey = addslashes($key2);
				break;
			}
		}
		
		// Set IdP Kind
		$IdPKind = '';
		if (isset($IDProvider['AttributeValue'])){
			foreach($IDProvider['AttributeValue'] as $IDPAttributeValue){
				foreach ($IDProvidersKind as $kindkey => $IDProviderKind){
					$IdPKindCheckFlg = false;
					if ($IDPAttributeValue == $kindkey){
						if (empty($IdPKind)){
							$IdPKind = '"'.$IDPAttributeValue.'"';
						} else {
							$IdPKind .= ', "'.$IDPAttributeValue.'"';
						}
						$IdPKindCheckFlg = true;
						break;
					}
				}
				if (!$IdPKindCheckFlg){
					if (empty($IdPKind)){
						$IdPKind = '"others"';
					} else {
						$IdPKind .= ', "others"';
					}
				}
			}
		} else {
			$IdPKind = '"others"';
		}
		
		// Get IdP Logo URL
		$IdPLogoURL = '';
		if (isset($IDProvider[$language]['Logo'])){
			$IdPLogoURL = $IDProvider[$language]['Logo']['url'];
		} elseif (isset($IDProvider['Logo'])) {
			$IdPLogoURL = $IDProvider['Logo']['url'];
		}
		
		// Get GeolocationHint latitude and longitude
		$IdPGeolocationHint = '';
		if (isset($IDProvider['GeolocationHint'])){
			foreach($IDProvider['GeolocationHint'] as $geolocation){
				if (empty($IdPGeolocationHint)){
					$IdPGeolocationHint = '"'.$geolocation.'"';
				} else {
					$IdPGeolocationHint .= ', "'.$geolocation.'"';
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
						$SearchIdPName .= ', "'.addslashes($value['Name']).'"';
					}
					break;
				}
			}
		}
		if (empty($SearchIdPName)){
			$SearchIdPName = '"'.$IdPName.'"';
		}
		
		$JSONIdPArray[] = <<<ENTRY

	"{$key}":{
		type:"{$IdPType}",
		name:"{$IdPName}",
		SAML1SSOurl:"{$IdPSSO}"
		}
ENTRY;
	
		$JSONIdPList = join(',', $JSONIdPArray);

		
		$JSONIncIdPArray[] = <<<ENTRY

	{
		entityid:"{$key}",
		type:"{$IdPType}",
		name:"{$IdPName}",
		search:[{$SearchIdPName}],
		SAML1SSOurl:"{$IdPSSO}",
		categoryName:"{$IdPCategoryName}",
		categoryKey:"{$IdPCategoryKey}",
		kind:[{$IdPKind}],
		logoURL:"{$IdPLogoURL}",
		geolocation:[{$IdPGeolocationHint}],
		registrationURL:"${IdPRegistrationURL}"
	}
ENTRY;
		
		if (count($mduiHintIDPs) > 0){
			foreach ($mduiHintIDPs as $hintIDP) {
				if ($key == $hintIDP) {
					$JSONIncIdPHintArray[] = <<<ENTRY

	{
		entityid: "{$key}",
		type: "{$IdPType}",
		name: "{$IdPName}",
		search: [{$SearchIdPName}],
		SAML1SSOurl: "{$IdPSSO}",
		categoryName:"{$hintIDPString}",
		categoryKey:"{$IdPCategoryKey}",
		kind: [{$IdPKind}],
		logoURL: "{$IdPLogoURL}",
		geolocation: [{$IdPGeolocationHint}],
		registrationURL: "${IdPRegistrationURL}"
	}
ENTRY;
					break;
				}
			}
		}
	}
	
	$JSONIdPList = join(',', $JSONIdPArray);
	$JSONIncCategoryList = join(',', $JSONIncCategoryArray);
	$JSONIncIdPList = join(',', $JSONIncIdPArray);
	$JSONIncIdPHintList = join(',', $JSONIncIdPHintArray);
	$selIdP = ($selIdP == '') ? $InitDisp : $selIdP ;
	
}

function printJscode_GlobalVariables(){
	echo <<<SCRIPT
	
	var suggest = '';
	var selkind = '';
	var old_hint_list = new Array();
	var refresh_flg = true;
	var discofeed_flg = true;
	var geolocation_ngflg = false;
	var geolocation_flg = true;
	var geolocation_ngflg = false;
	var hintGeolocationFlg = false;
	var myMap = '';
	var markersList = new Array();
	var infowindowsList = new Array();
	var clientGeolocation = '';
	
SCRIPT;
}

?>
