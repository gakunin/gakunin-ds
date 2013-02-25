<?php
// WAYF Identity Provider Configuration file

// Find below some example entries of Identity Providers, categories and 
// cascaded WAYFs
// The keys of $IDProviders must correspond to the entityId of the 
// Identity Providers or a unique value in case of a cascaded WAYF/DS or 
// a category. In the case of a category, the key must correspond to the the 
// Type value of Identity Provider entries.
// The sequence of IdPs and SPs play a role. No sorting is done.

// A general entry for an IdP can consist of the form:
// Type:   [Optional]    Type of the entry. Default type will 
//                       be 'unknown' if not specified.
//                       Categories should have the type 'category'
//                       An entry for a cascaded WAYF that the user shall be
//                        redirected to should have the type 'wayf'
// Name:   [Mandatory]   Default name to display in drop-down list
// [en|it|fr||de|pt][Name]: [Optional] Display name in other languages
// SSO:    [Mandatory]   Should be the SAML1 SSO endpoint of the IdP
// Realm:  [Optional]    Kerberos Realm
// IP[]:   [Optional]    IP ranges of that organizations that can be used to guess
//                       a user's Identity Provider
// Index:  [Optional]    An alphanumerical value that is used for sorting 
//                       categories and Identity Provider in ascending order 
//                       if the Identity Providers are parsed from metadata.
//                       This is only relevant if 
//                       $includeLocalConfEntries = true

// A category entry can be used to group multiple IdP entries into a optgroup
// The category entries should look like:
// Name:   [Mandatory]   Default name to display in drop-down list
// [en|it|fr||de|pt][Name]: [Optional] Display name in other languages
// Type:   'category'    Category type 
// As stated above, the sequence of entries is important. So, one is completely
// flexible when it comes to ordering the category and IdP entries.
// 

// Category

//
// 北海道
$IDProviders['hokkaido'] = array (
		'Type' => 'category',
		'en' => array ('Name' => 'Hokkaido'),
		'ja' => array ('Name' => '北海道'),
		'Name' => 'Hokkaido',
		'Index' => '001',
);

//
// 東北
$IDProviders['tohoku'] = array (
		'Type' => 'category',
		'en' => array ('Name' => 'Tohoku'),
		'ja' => array ('Name' => '東北'),
		'Name' => 'Tohoku',
		'Index' => '002',
);

//
// 関東
$IDProviders['kanto'] = array (
		'Type' => 'category',
		'en' => array ('Name' => 'Kanto'),
		'ja' => array ('Name' => '関東'),
		'Name' => 'Kanto',
		'Index' => '003',
);

//
// 中部
$IDProviders['chubu'] = array (
		'Type' => 'category',
		'en' => array ('Name' => 'Chubu'),
		'ja' => array ('Name' => '中部'),
		'Name' => 'Chubu',
		'Index' => '004',
);

//
// 近畿 
$IDProviders['kinki'] = array (
		'Type' => 'category',
		'en' => array ('Name' => 'Kinki'),
		'ja' => array ('Name' => '近畿'),
		'Name' => 'Kinki',
		'Index' => '005',
);

//
// 中国
$IDProviders['chugoku'] = array (
		'Type' => 'category',
		'en' => array ('Name' => 'Chugoku'),
		'ja' => array ('Name' => '中国'),
		'Name' => 'Chugoku',
		'Index' => '006',
);

//
// 四国
$IDProviders['shikoku'] = array (
		'Type' => 'category',
		'en' => array ('Name' => 'Shikoku'),
		'ja' => array ('Name' => '四国'),
		'Name' => 'Shikoku',
		'Index' => '007',
);

//
// 九州
$IDProviders['kyushu'] = array (
		'Type' => 'category',
		'en' => array ('Name' => 'Kyushu'),
		'ja' => array ('Name' => '九州'),
		'Name' => 'Kyushu',
		'Index' => '008',
);

//
// その他
$IDProviders['others'] = array (
		'Type' => 'category',
		'en' => array ('Name' => 'Others'),
		'ja' => array ('Name' => 'その他'),
		'Name' => 'Others',
		'Index' => '009',
);

//
// 全て
$IDProvidersKind['all'] = array (
		'Type' => 'kind',
		'en' => array ('Name' => 'all'),
		'ja' => array ('Name' => '全て'),
		'Name' => 'All',
		'Default' => 'checked',
		'Index' => '010',
);

//
// 大学
$IDProvidersKind['university'] = array (
		'Type' => 'kind',
		'en' => array ('Name' => 'university'),
		'ja' => array ('Name' => '大学'),
		'Name' => 'University',
		'Default' => '',
		'Index' => '011',
);

//
// 短大
$IDProvidersKind['tertiaryb'] = array (
		'Type' => 'kind',
		'en' => array ('Name' => 'tertiaryb'),
		'ja' => array ('Name' => '短大'),
		'Name' => 'Tertiaryb',
		'Default' => '',
		'Index' => '012',
);

//
// 高専
$IDProvidersKind['uppersecondary'] = array (
		'Type' => 'kind',
		'en' => array ('Name' => 'uppersecondary'),
		'ja' => array ('Name' => '高専'),
		'Name' => 'Uppersecondary',
		'Default' => '',
		'Index' => '013',
);

//
// 研究所
$IDProvidersKind['vho'] = array (
		'Type' => 'kind',
		'en' => array ('Name' => 'vho'),
		'ja' => array ('Name' => '研究所'),
		'Name' => 'Vho',
		'Default' => '',
		'Index' => '014',
);

//
// その他
$IDProvidersKind['others'] = array (
		'Type' => 'kind',
		'en' => array ('Name' => 'others'),
		'ja' => array ('Name' => 'その他'),
		'Name' => 'Others',
		'Default' => '',
		'Index' => '015',
);

?>
