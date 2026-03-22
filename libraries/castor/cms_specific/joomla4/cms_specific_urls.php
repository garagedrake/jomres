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
	set_showtime('live_site', str_replace('/castor', '', get_showtime('live_site')));
}

	define('CASTOR_ADMINISTRATORDIRECTORY', 'administrator');

//find castor itemId
	$castorItemid = 0;

if (!defined('AUTO_UPGRADE') && !isset($_REQUEST['itemId'])) {
	$app = JFactory::getApplication();
	$menu = $app->getMenu();
	$wotweislookingfor = 'index.php?option=com_castor&view=default';

	$menuItem = $menu->getItems('link', $wotweislookingfor, $firstonly = true);

	if ($menuItem) {
		$castorItemid = (int)$menuItem->id;
	} else {
		$items = $menu->getMenu();
		if (!empty($items)) {
			foreach ($items as $key => $val) {
				if ($val->link == $wotweislookingfor) {
					$castorItemid = $key;
				}
			}
		}
	}
} elseif (isset($_REQUEST['itemId'])) {
	$castorItemid = (int)$_REQUEST['itemId'];
}

//set castor itemid
	set_showtime('castorItemid', $castorItemid);

//tmpl
	$tmpl = castorGetParam($_GET, 'tmpl', '');

//component wrapped enabled, or &tmpl=castor present in the url
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
	$lang = get_showtime('lang_shortcode');

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

if (isset($_REQUEST['lang'])) {
	$lang = castorGetParam($_REQUEST, 'lang', '');
}

//castor specific urls
	define('CASTOR_SITEPAGE_URL_NOSEF', get_showtime('live_site').'/index.php?option=com_castor&Itemid='.$castorItemid.'&lang='.$lang.$tmpl.$lang_param);
	define('CASTOR_SITEPAGE_URL_AJAX', get_showtime('live_site').'/'.'index.php?option=com_castor&no_html=1&jrajax=1&Itemid='.$castorItemid.'&lang='.$lang.$tmpl.$lang_param);
	define('CASTOR_SITEPAGE_URL_ADMIN', get_showtime('live_site').'/'.CASTOR_ADMINISTRATORDIRECTORY.'/index.php?option=com_castor'.$tmpl.$lang_param);
	define('CASTOR_SITEPAGE_URL_ADMIN_AJAX', get_showtime('live_site').'/'.CASTOR_ADMINISTRATORDIRECTORY.'/index.php?option=com_castor&no_html=1&jrajax=1'.$lang_param.$tmpl);

if (class_exists('JFactory')) {
	$config = JFactory::getConfig();
	if ($config->get('sef') == '1') {
		define('CASTOR_SITEPAGE_URL', 'index.php?option=com_castor&Itemid='.$castorItemid.$tmpl.'&lang='.$lang.$lang_param);
	} else {
		define('CASTOR_SITEPAGE_URL', get_showtime('live_site').'/index.php?option=com_castor&Itemid='.$castorItemid.$tmpl.'&lang='.$lang.$lang_param);
	}
} else {
	define('CASTOR_SITEPAGE_URL', get_showtime('live_site').'/index.php?option=com_castor&Itemid='.$castorItemid.$tmpl.'&lang='.$lang.$lang_param);
}

