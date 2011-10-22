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
$IDProviders['kyusyu'] = array (
		'Type' => 'category',
		'en' => array ('Name' => 'Kyusyu'),
		'ja' => array ('Name' => '九州'),
		'Name' => 'Kyusyu',
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


// IDP entries

// 国立情報学研究所
$IDProviders['https://idp.nii.ac.jp/idp/shibboleth'] = 
  array (
    'Type' => 'kanto',
    'Index' => 'PI0001JP',
);

// 名古屋大学
$IDProviders['https://shib.itc.nagoya-u.ac.jp/idp/shibboleth'] = 
  array (
    'Type' => 'chubu',
    'Index' => 'PI0002JP',
);

// 山形大学
$IDProviders['https://upki.yamagata-u.ac.jp/idp/shibboleth'] = 
  array (
    'Type' => 'tohoku',
    'Index' => 'PI0003JP',
);

// 千葉大学
$IDProviders['https://upki-idp.chiba-u.jp/idp/shibboleth'] = 
  array (
    'Type' => 'kanto',
    'Index' => 'PI0004JP',
);

// 京都大学
$IDProviders['https://authidp1.iimc.kyoto-u.ac.jp/idp/shibboleth'] = 
  array (
    'Type' => 'kinki',
    'Index' => 'PI0005JP',
);

// 広島大学
$IDProviders['https://idp.hiroshima-u.ac.jp/idp/shibboleth'] = 
  array (
    'Type' => 'chugoku',
    'Index' => 'PI0006JP',
);

// 金沢大学
$IDProviders['https://auth-sso.db.kanazawa-u.ac.jp/idp/shibboleth'] = 
  array (
    'Type' => 'chubu',
    'Index' => 'PI0007JP',
);

// 北海道大学
$IDProviders['https://shib-idp01.iic.hokudai.ac.jp/idp/shibboleth'] = 
  array (
    'Type' => 'hokkaido',
    'Index' => 'PI0008JP',
);

// 筑波大学
$IDProviders['https://idp.account.tsukuba.ac.jp/idp/shibboleth'] = 
  array (
    'Type' => 'kanto',
    'Index' => 'PI0009JP',
);

// 佐賀大学
$IDProviders['https://ssoidp.cc.saga-u.ac.jp/idp/shibboleth'] = 
  array (
    'Type' => 'kyusyu',
    'Index' => 'PI0010JP',
);

// 山口大学
$IDProviders['https://idp.cc.yamaguchi-u.ac.jp/idp/shibboleth'] = 
  array (
    'Type' => 'chugoku',
    'Index' => 'PI0011JP',
);

// 成城大学
$IDProviders['https://asura.seijo.ac.jp/idp/shibboleth'] = 
  array (
    'Type' => 'kanto',
    'Index' => 'PI0012JP',
);

// 東邦大学
$IDProviders['https://upki.toho-u.ac.jp/idp/shibboleth'] = 
  array (
    'Type' => 'kanto',
    'Index' => 'PI0013JP',
);

// 三重大学
$IDProviders['https://fed.mie-u.ac.jp/idp'] = 
  array (
    'Type' => 'chubu',
    'Index' => 'PI0014JP',
);

// 日本大学
$IDProviders['https://shibboleth.nihon-u.ac.jp/idp/shibboleth'] = 
  array (
    'Type' => 'kanto',
    'Index' => 'PI0015JP',
);

// 旭川医科大学
$IDProviders['https://idp.asahikawa-med.ac.jp/idp/shibboleth'] = 
  array (
    'Type' => 'hokkaido',
    'Index' => 'PI0016JP',
);

// 東京農工大学
$IDProviders['https://idp2.med.tuat.ac.jp/idp/shibboleth'] = 
  array (
    'Type' => 'kanto',
    'Index' => 'PI0017JP',
);

//岡山大学
$IDProviders['https://odidp.cc.okayama-u.ac.jp/idp/shibboleth'] =
  array (
    'Type' => 'chugoku',
    'Index' => 'PI0018JP',
);

//九州工業大学
$IDProviders['https://idp.isc.kyutech.ac.jp/idp/shibboleth'] =
  array (
    'Type' => 'kyusyu',
    'Index' => 'PI0019JP',
);

// 京都産業大学
$IDProviders['https://gakunin.kyoto-su.ac.jp/idp'] =
  array (
    'Type' => 'kinki',
    'Index' => 'PI0020JP',
);

// 立教大学
$IDProviders['https://upki-idp.rikkyo.ac.jp/idp/shibboleth'] =
  array (
    'Type' => 'kanto',
    'Index' => 'PI0021JP',
);

// 九州大学
$IDProviders['https://idp.kyushu-u.ac.jp/idp/shibboleth'] =
  array (
    'Type' => 'kyusyu',
    'Index' => 'PI0022JP',
);

// 明治大学図書館
$IDProviders['https://servs.lib.meiji.ac.jp/idp/shibboleth'] =
  array (
    'Type' => 'kanto',
    'Index' => 'PI0023JP',
);

// 神戸大学
$IDProviders['https://fed.center.kobe-u.ac.jp/idp/shibboleth'] =
  array (
    'Type' => 'kinki',
    'Index' => 'PI0024JP',
);

// 学認
$IDProviders['https://idp.gakunin.nii.ac.jp/idp/shibboleth'] =
  array (
    'Type' => 'others',
    'Index' => 'PI0025JP',
);

// 信州大学 
$IDProviders['https://gakunin.ealps.shinshu-u.ac.jp/idp/shibboleth'] =
  array (
    'Type' => 'chubu',
    'Index' => 'PI0026JP',
);

// 自治医科大学
$IDProviders['https://ws1.jichi.ac.jp/idp/shibboleth'] =
  array (
    'Type' => 'kanto',
    'Index' => 'PI0027JP',
);

// 名古屋工業大学
$IDProviders['https://gknidp.ict.nitech.ac.jp/idp/shibboleth'] =
  array (
    'Type' => 'chubu',
    'Index' => 'PI0028JP',
);

// 山梨大学
$IDProviders['https://idp.yamanashi.ac.jp/idp/shibboleth'] =
  array (
    'Type' => 'chubu',
    'Index' => 'PI0029JP',
);

// 広島市立大学
$IDProviders['https://fed.ipc.hiroshima-cu.ac.jp/idp/shibboleth'] =
  array (
    'Type' => 'chugoku',
    'Index' => 'PI0030JP',
);

?>
