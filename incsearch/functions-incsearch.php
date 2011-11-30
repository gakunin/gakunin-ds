<?php

/******************************************************************************/
// Parses the reverse dns lookup hostname out of a string and returns domain
function getDomainNameFromURIHint_IncSearch($HintKeyList){

	global $IDProviders, $useMduiHintMax;

	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	if ($hostname == $_SERVER['REMOTE_ADDR']){
		return $HintKeyList;
	}
	// Do we still have something
	$domainname = getDomainNameFromURI($hostname);
	if ($domainname != ''){
		// Find a matching IdP SSO, must be matching the IdP urn
		// or at least the last part of the urn
		foreach ($IDProviders as $key => $idp){
			if (is_array($idp) && array_key_exists("DomainHint", $idp)) {
				foreach( $idp["DomainHint"] as $domainhint ) {
					if (count($HintKeyList) == $useMduiHintMax) {
						return $HintKeyList;
					}
					if (preg_match('/'.$domainhint.'$/', $hostname)){
						if (!checkHintIDP($key, $HintKeyList)) {
							array_push($HintKeyList, $key);
						}
					}
				}
			}
		}
	}
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
				if (getNetMatch($network, $clientIP)) {
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
