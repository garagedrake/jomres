<?php
/**
 * Core file.
 *
 * @author Vince Wooll <sales@castor.net>
 *
 *  @version Castor 10.7.2
 *
 * @copyright	2005-2023 Vince Wooll
 * Castor is currently available for use in all personal or commercial projects under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly
 **/

// ################################################################
defined('_CASTOR_INITCHECK') or die('');
// ################################################################
	
	/**
	 *
	 * @package Castor\Core\CMS_Specific
	 *
	 */

$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
$jrConfig = $siteConfig->get();

if (defined('AUTO_UPGRADE')) {
	set_showtime('live_site', str_replace('/castor/', '/', get_showtime('live_site')));
}

define('CASTOR_ADMINISTRATORDIRECTORY', 'wp-admin');

//let`s find if we have some language set
$currentBlogLang = str_replace('_', '-', get_locale());

$keyword = '[castor:'.strtolower($currentBlogLang).']';

//find castor itemid
$castorItemid = 0;

if (!defined('AUTO_UPGRADE')) {
	$castorItemid = 0;

	$query = "SELECT `ID` FROM #__posts WHERE LOWER(`post_content`) LIKE '%".$keyword."%' AND `post_status` = 'publish' AND `post_type` = 'page' LIMIT 1";
	$itemQueryRes = (int) doSelectSql($query, 1);

	$itemIdFound = false;

	if ($itemQueryRes > 0) {
		$itemIdFound = true;
		$castorItemid = $itemQueryRes;
	}
}

//set castor itemid
set_showtime('castorItemid', $castorItemid);

//tmpl
$tmpl = castorGetParam($_GET, 'tmpl', '');

if ($tmpl == get_showtime('tmplcomponent') && !isset($_REQUEST[ 'nofollowtmpl' ]) && !castor_cmsspecific_areweinadminarea()) {
	$tmpl = '&tmpl='.get_showtime('tmplcomponent');
}

//is_wrapped
if (isset($_REQUEST[ 'is_wrapped' ])) {
	if ($_REQUEST[ 'is_wrapped' ] == '1') {
		$tmpl .= '&is_wrapped=1';
	}
}

//menuoff
	if (isset($_REQUEST[ 'menuoff' ])) {
		if ($_REQUEST[ 'menuoff' ] == '1') {
			set_showtime('menuoff', true);
			if (!isset($_REQUEST[ 'nofollowmenuoff' ])) {
				$tmpl .= '&menuoff=1';
			} else {
				$tmpl .= '&menuoff=0';
			}
		} else {
			$tmpl .= '&menuoff=0';
			set_showtime('menuoff', false);
		}
	}

//topoff
if (isset($_REQUEST[ 'topoff' ])) {
	if ($_REQUEST[ 'topoff' ] == '1') {
		$tmpl .= '&topoff=1';
		set_showtime('topoff', true);
	} else {
		$tmpl .= '&topoff=0';
		set_showtime('topoff', false);
	}
}

//cms lang
$lang = castorGetParam($_GET, 'lang', '');
if ($lang != '') {
	$lang = '&lang='.substr($lang, 0, 2);
}

//Castor specific lang switching
$lang_param = '';
if (isset($_REQUEST[ 'castorlang' ])) {
	$castorlang = castorGetParam($_REQUEST, 'castorlang', '');
	$castor_language = castor_singleton_abstract::getInstance('castor_language');
	$castor_language->init();
	if ($castorlang != '' && isset($castor_language->datepicker_crossref[$castorlang])) {
		$lang_param = '&castorlang='.$castorlang;
	}
}

//castor specific urls
define('CASTOR_SITEPAGE_URL_NOSEF', get_showtime('live_site').'/index.php?option=com_castor&page_id='.$castorItemid.$tmpl.$lang.$lang_param);
define('CASTOR_SITEPAGE_URL_AJAX', get_showtime('live_site').'/index.php?action=castor_ajax&no_html=1&jrajax=1&jr_wp_source=frontend&option=com_castor&page_id='.$castorItemid.$tmpl.$lang.$lang_param);

define('CASTOR_SITEPAGE_URL_ADMIN', get_showtime('live_site').'/wp-admin/admin.php?page=castor/castor.php&jr_wp_source=admin&option=com_castor'.$tmpl.$lang.$lang_param);
define('CASTOR_SITEPAGE_URL_ADMIN_AJAX', get_showtime('live_site').'/wp-admin/admin-ajax.php?action=castor_ajax&no_html=1&jrajax=1&jr_wp_source=admin&option=com_castor'.$tmpl.$lang.$lang_param);

if (get_showtime('sef') == '1') {
	define('CASTOR_SITEPAGE_URL', get_permalink($castorItemid).'?option=com_castor'.$tmpl.$lang.$lang_param);
} else {
	define('CASTOR_SITEPAGE_URL', get_showtime('live_site').'/index.php?option=com_castor&page_id='.$castorItemid.$tmpl.$lang.$lang_param);
}

