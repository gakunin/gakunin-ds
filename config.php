<?php // Copyright (c) 2011, SWITCH - Serving Swiss Universities

//******************************************************************************
// This file contains the WAYF/DS configuration. Adapt the settings to reflect
// your environment and then do some testing before deploying the WAYF.
//******************************************************************************

// 1. Language settings
//*********************
//$defaultLanguage = 'en'; 
$defaultLanguage = 'ja';

// 2. Cookie settings
//*******************

// Domain within the WAYF cookei shall be readable. Must start with a .
//$commonDomain = '.switch.ch';
//$commonDomain = '.nii.ac.jp';
$commonDomain = '.ds.gakunin.nii.ac.jp';

// Optionnal cookie name prefix in case you run several 
// instances of the WAYF in the same domain. 
// Example: $cookieNamePrefix = '_mywayf';
$cookieNamePrefix = '';

// Names of the cookies where to store the settings to temporarily
// redirect users transparently to their last selected IdP
$redirectCookieName = $cookieNamePrefix.'_redirect_user_idp';
$redirectStateCookieName = $cookieNamePrefix.'_redirection_state';

// Stores last selected IdPs 
// This value shouldn't be changed because _saml_idp is the officilly
// defined name in the SAML specification
$SAMLDomainCookieName = $cookieNamePrefix.'_saml_idp';

// Stores last selected SP
// This value can be choosen as you like because it is something specific
// to this WAYF implementation. It can be used to display help/contact 
// information on a page in the same domain as $commonDomain by accessing
// the federation metadata and parsing out the contact information of the 
// selected IdP and SP using $SAMLDomainCookieName and $SPCookieName
$SPCookieName = $cookieNamePrefix.'_saml_sp';

// If true, "secure" attribute is set
$cookieSecure = true;


// 3. Features and extensions
//***************************

// Whether to show the checkbox to permanently remember a setting
$showPermanentSetting = true;

// Set to true in order to enable reading the Identity Provider from a SAML2 
// metadata file defined below in $metadataFile
$useSAML2Metadata = true; 

// If ture parsed metadata shall have precedence if there are entries defined 
// in metadata as well as the local IDProviders configuration file.
// Only relevant if $useSAML2Metadata is true
$SAML2MetaOverLocalConf = false;

// If includeLocalConfEntries parameter is set to true, Identity Providers
// not listed in metadata but defined in the local IDProviders file will also
// be displayed in the drop down list. This is required if you need to add 
// local exceptions over the federation metadata
// Only relevant if $useSAML2Metadata is true
$includeLocalConfEntries = true;

// Whether the return parameter is checked against SAML2 metadata or not
// The Discovery Service specification says the DS SHOULD check this in order
// to mitigate phising problems
// This check only is active if $useSAML2Metadata = true 
$enableDSReturnParamCheck = true;

// If true, not only the the URLs defined in the metadata extension 
// <idpdisc:DiscoveryResponse> are used for the check but also the hostnames
// of the assertion consumer URLs. The hostnames are compared against the 
// hostname used in the return parameter
// This feature is especially useful in case metadata doesn't contain the
// <idpdisc:DiscoveryResponse> extension. However, enabling this feature also
// reduces the security of the check.
// This feature only is active if $enableDSReturnParamCheck = true 
// and if  $useSAML2Metadata = true 
$useACURLsForReturnParamCheck = false;

// Whether to turn on Kerberos support for IdP preselection
$useKerberos = false;

// If true, the users IP is used for a reverse DNS lookup whose
// resulting domain name then is matched with the URN values of the IdPs
//$useReverseDNSLookup = false;
$useReverseDNSLookup = true;

// Whether the JavaScript for embedding the WAYF
// on a remote site shall be generated or not
$useEmbeddedWAYF = true;

// Whether to enable logging of WAYF/DS requests
// If turned on make sure to also configure $WAYFLogFile
$useLogging = true; 

// Whether or not to add the entityID of the preselected IdP to the
// exported JSON/Text/PHP Code
// You have to be aware that if this value is set to true, any web page
// in the world can easily find out with a high probability from which 
// organization a user is from. This could be misused for various kinds of 
// things and even for phishing attacks. Therefore, only enable this feature
// if you know what you are doing!
$exportPreselectedIdP = false;

// Incremental Search
$useAutocompleteIdP = true;

// Referer Check
$useRefererForPrivacyProtection = true;


// 4. Look and feel settings
//**************************

// Name of the federation
//$federationName = 'SWITCHaai Federation';
$federationName = 'GakuNin';

// URL to send user to when clicking on federation logo
//$federationURL = 'http://www.switch.ch/aai/';
//$federationURL = 'https://upki-portal.nii.ac.jp/docs/fed';
$federationURL = 'https://www.gakunin.jp/';

// Use an absolute URL in case you want to use the embedded WAYF
//$imageURL = 'https://'.$_SERVER['SERVER_NAME'].'/SWITCHaai/images';
//$imageURL = 'https://upki-test-ds.nii.ac.jp/DS2/images';
$imageURL = 'https://ds.gakunin.nii.ac.jp/GakuNinDS/images';

// URL to the logo that shall be displayed
//$logoURL = $imageURL.'/switch-aai-transparent.png'; 
$logoURL = $imageURL.'/gakunin.png';

// URL to the small logo that shall be displayed in the embedded WAYF if dimensions are small
//$smallLogoURL = $imageURL.'/switch-aai-transparent-small.png';
$smallLogoURL = $imageURL.'/gakunin-seal.png';

$alertURL = $imageURL.'/alert.gif';
$dropdownUpURL = $imageURL.'/dropdown_up.png';
$dropdownDnURL = $imageURL.'/dropdown_down.png';

$incsearchURL = 'https://ds.gakunin.nii.ac.jp/GakuNinDS/incsearch';
$incsearchLibURL = $incsearchURL.'/suggest.js';
$incsearchCssURL = $incsearchURL.'/suggest.css';
$ajaxLibURL = $incsearchURL.'/jquery.js';
$ajaxFlickLibURL = $incsearchURL.'/jquery.flickable.js';


// 5. Files and path settings
//***************************

// Set both config files to the same value if you don't want to use the 
// the WAYF to read a (potential) automatically generated file that undergoes
// some plausability checks before being used
$IDPConfigFile = 'IDProvider.conf.php'; // Config file
$backupIDPConfigFile = 'IDProvider.conf.php'; // Backup config file

// Use $metadataFile as source federation's metadata.
//$metadataFile = '/etc/shibboleth/metadata.switchaai.xml';
//$metadataFile = 'https://metadata.gakunin.nii.ac.jp/gakunin-metadata.xml';
$metadataFile = '/var/www/html/gakunin-metadata.xml';

// File to store the parsed IdP list
// Will be updated automatically if the metadataFile modification time
// is more recent than this file's
// The user running the script must have permission to create $metadataIdpFile
$metadataIDPFile = 'IDProvider.metadata.php';

// File to store the parsed SP list.
// Will be updated automatically if the metadataFile modification time
// is more recent than this file's
// The user running the script must have permission to create $metadataIdpFile
$metadataSPFile = 'SProvider.metadata.php';

// File to use as the lock file for writing the parsed IdP and SP lists.
// The user running the script must have permission to write $metadataLockFile
$metadataLockFile = '/tmp/wayf_metadata.lock';

// Where to log the access
// Make sure the web server user has write access to this file!
//$WAYFLogFile = '/var/log/apache2/wayf.log'; 
$WAYFLogFile = 'logs/wayf.log';


// 6. Other settings
//******************

// A Kerboros-protected soft link back to this script!
//$kerberosRedirectURL = '/SWITCHaai/kerberosRedirect.php';

// Development mode settings
//**************************
// If the development mode is activated, PHP errors and warnings will be displayed
//$developmentMode = false;
$developmentMode = true;

?>
