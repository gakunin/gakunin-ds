<?php

// This file is used to dynamically create the list of IdPs to be 
// displayed for the WAYF/DS service based on the federation metadata.
// Configuration parameters are specified in config.php.
//
// The list of Identity Providers can also be updated by running the script
// readCategory.php periodically as web server user, e.g. with a cron entry like:
// 5 * * * * /usr/bin/php readCategory.php > /dev/null

// Could be used for testing purposes or to facilitate startup confiduration.
// Results are dumped in $IDPConfigFile (see config.php)

$IDPCategoryFile = 'IDProvider.category.php';

// Load configuration files
require('config.php');
require($IDPCategoryFile);

// Check configuration
if (!isset($metadataFile) 
    || !file_exists($metadataFile) 
    || trim(@file_get_contents($metadataFile)) == '') {
	exit ("Exiting: File ".$metadataFile." is empty or does not exist\n");
}

// Get an exclusive lock to generate our parsed Category files.
if (($lockFp = fopen($metadataLockFile, 'a+')) === false) {
	$errorMsg = 'Could not open lock file '.$metadataLockFile;
	die($errorMsg);
}
if (flock($lockFp, LOCK_EX) === false) { 
	$errorMsg = 'Could not lock file '.$metadataLockFile;
	die($errorMsg);
}

echo 'Parsing metadata file '.$metadataFile."\n";
list($metadataIDProviders) = parseMetadata($metadataFile, $defaultLanguage);

// If $metadataIDProviders is not FALSE, dump results in $metadataIDPFile.
if(is_array($metadataIDProviders)){ 

	echo 'Dumping parsed Identity Providers Category to file '.$IDPConfigFile."\n";
	dumpFile($IDPConfigFile, $metadataIDProviders, 'IDProviders');
}

// Release the lock, and close.
flock($lockFp, LOCK_UN);
fclose($lockFp);
		
// If $metadataIDProviders is not FALSE, update $IDProviders and print the Identity Providers lists.
if(is_array($metadataIDProviders)){ 

	echo "Printing parsed Category List:\n";
	print_r($IDProviders);

	echo "Printing effective Identity Providers Category:\n";
	print_r($metadataIDProviders);
}

/*****************************************************************************/
// Function parseMetadata, parses metadata file and returns Array($IdPs, SPs)  or
// Array(false, false) if error occurs while parsing metadata file
function parseMetadata($metadataFile, $defaultLanguage){
	
	// mdui:Keywords
	// data1,data2,cotegory:categoryKey,data3,data4,...
	$splitCategory = 'category'; 
	$splitChar1 = ','; 
	$splitChar2 = ':'; 
	
	if(!file_exists($metadataFile)){
		$errorMsg = 'File '.$metadataFile." does not exist"; 
		echo $errorMsg."\n";
		return Array(false, false);
	}

	if(!is_readable($metadataFile)){
		$errorMsg = 'File '.$metadataFile." cannot be read due to insufficient permissions"; 
		echo $errorMsg."\n";
		return Array(false, false);
	}
	
	$doc = new DOMDocument();
	if(!$doc->load( $metadataFile )){
		$errorMsg = 'Could not parse metadata file '.$metadataFile; 
		echo $errorMsg."\n";
		return Array(false, false);
	}
	
	$EntityDescriptors = $doc->getElementsByTagNameNS( 'urn:oasis:names:tc:SAML:2.0:metadata', 'EntityDescriptor' );
	
	$metadataIDProviders = Array();
	foreach( $EntityDescriptors as $EntityDescriptor ){
		foreach($EntityDescriptor->childNodes as $RoleDescriptor) {
			$nodeName = $RoleDescriptor->nodeName;
			$nodeName = preg_replace('/^(\w+\:)/', '', $nodeName);
			switch($nodeName){
				case 'IDPSSODescriptor':
					$entityID = $EntityDescriptor->getAttribute('entityID');
					$Index = $EntityDescriptor->getAttribute('ID');
					$mduiKeywords = getMduiNodeValue($EntityDescriptor, 'keywords');
					$IDP = Array();
					if ($Index != '') {
						$IDP['Index'] = $Index;
					}
					if ($mduiKeywords != '') {
						$IDP['Type'] = strtolower($mduiKeywords);
					}
					$metadataIDProviders[$entityID] = $IDP;
					break;
				default:
			}
		}
	}
	
	// Output result
	$infoMsg = "Successfully parsed metadata file ".$metadataFile. ". Found ".count($metadataIDProviders)." IdPs";
	echo $infoMsg."\n";
	
	return Array($metadataIDProviders);
}

/******************************************************************************/
// Processes an IDPRoleDescriptor XML node and returns an IDP entry or false if 
// something went wrong
function getMduiNodeValue($xmlNode, $targetNodeName){
	// mdui:Keywords
	// data1,data2,cotegory:categoryKey,data3,data4,...
	$splitCategory = 'category'; 
	$splitChar1 = ','; 
	$splitChar2 = ':'; 
	$ret = '';

	$targetNodeName = strtolower($targetNodeName);
	foreach($xmlNode->childNodes as $xmlChildNode) {
		$nodeName = $xmlChildNode->nodeName;
		$nodeName = preg_replace('/^(\w+\:)/', '', $nodeName);
		$nodeName = strtolower($nodeName);
		switch($nodeName){
			case 'uiinfo':
				foreach ($xmlChildNode->childNodes as $mduiNode){
					$nodeName = $mduiNode->nodeName;
					$nodeName = preg_replace('/^(\w+\:)/', '', $nodeName);
					$nodeName = strtolower($nodeName);
					if ($targetNodeName == $nodeName) {
						$keywords = $mduiNode->nodeValue;
						if ($keywords != '') {
							$keywordsArray = explode($splitChar1, $keywords);
							foreach($keywordsArray as $cols) {
								$vals = explode($splitChar2, $cols);
								if ($splitCategory == $vals[0]) {
									$ret = $vals[1];
									break;
								}
							}
							if ($ret != '') {
								break;
							}
						}
					}
				}
				break;
			case 'idpssodescriptor':
			case 'extensions':
				$ret = getMduiNodeValue($xmlChildNode, $targetNodeName);
			default:
		}
		if ($ret != '') {
			break;
		}
	}
	return $ret;	
}

/******************************************************************************/
// Dump variable to a file 
function dumpFile($dumpFile, $providers, $variableName){
	global $IDPCategoryFile;
	if(($fp = fopen($dumpFile, 'w')) !== false){
		
		fwrite($fp, file_get_contents($IDPCategoryFile)."\n");
		fwrite($fp, "<?php\n\n");
		fwrite($fp, "// This file was automatically generated by readCategory.php\n");
		fwrite($fp, "// Don't edit!\n\n");
		fwrite($fp, "// IDP entries\n\n");

		
		foreach($providers as $entityId => $val) {
			fwrite($fp, '$'.$variableName.'[\''.$entityId.'\'] ='."\n");
			fwrite($fp, var_export($val, true));
			fwrite($fp, ";\n\n");
		}

		fwrite($fp, "?>");
			
		fclose($fp);
	} else {
		$errorMsg = 'Could not open file '.$dumpFile.' for writting';
		echo $errorMsg."\n";
	}
}

?>
