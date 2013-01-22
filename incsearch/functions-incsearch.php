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

?>
