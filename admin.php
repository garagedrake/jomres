<?php
/**
 * Core file.
 *
 * @author Vince Wooll <sales@castor.net>
 *
 *  @version Castor 10.7.2
 *
 * @copyright	2005-2023 Vince Wooll
 * Castor (tm) PHP, CSS & Javascript files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly
 **/

//#################################################################
defined('_CASTOR_INITCHECK') or die('');
defined('_CASTOR_INITCHECK_ADMIN') or die('');
//#################################################################

/**
 * Administrator area main script.
 * 
 * Builds the administrator area page.
 * 
 **/
 
ob_start('removeBOMadmin');

@ini_set('max_execution_time', '480');

require_once dirname(__FILE__).'/integration.php';

try {
	//minicomponents object
	$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');

	//site config object
	$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
	$jrConfig = $siteConfig->get();

	//request log
	if ($jrConfig['development_production'] == 'development') {
		request_log();
	}

	jr_import('castor_api_capability_test');
	$castor_api_capability_test = new castor_api_capability_test();
	$castor_api_capability_test->is_system_capable();

	//get all properties in system.
	$castor_properties = castor_singleton_abstract::getInstance('castor_properties');
	$castor_properties->get_all_properties();

    //trigger 07090 event (see README/md)
    $MiniComponents->triggerEvent('07090');

	//language object - load default language file for context
	$castor_language = castor_singleton_abstract::getInstance('castor_language');
    $castor_language->init();
	$castor_language->get_language();

	//custom text object
	$customTextObj = castor_singleton_abstract::getInstance('custom_text');
	
	//trigger 00001 event
	$MiniComponents->triggerEvent('00001');

	//trigger 00002 event
	$MiniComponents->triggerEvent('00002');

	//user object
	$thisJRUser = castor_singleton_abstract::getInstance('jr_user');

	//input filtering
	$MiniComponents->triggerEvent('00003');

	//cron jobs
	$cron = castor_singleton_abstract::getInstance('castor_cron');
	if ($cron->method == 'Minicomponent' && !AJAXCALL) {
		$cron->triggerJobs();
	}

	//session
	$tmpBookingHandler = castor_singleton_abstract::getInstance('castor_temp_booking_handler');
	$tmpBookingHandler->initBookingSession();

	$castorsession = $tmpBookingHandler->getCastorsession();
	set_showtime('castorsession', $castorsession);

	//set task showtime
	$task = castorGetParam($_REQUEST, 'task', 'cpanel');
	$task = str_replace('&#60;x&#62;', '', $task);
	set_showtime('task', $task);

	//currency conversion object
	$castor_currency_exchange_rates = castor_singleton_abstract::getInstance('castor_currency_exchange_rates');
	
	//set currency code to the appropriate one for the detected location
	$castor_geolocation = castor_singleton_abstract::getInstance('castor_geolocation');
	$castor_geolocation->auto_set_user_currency_code();

	require_once CASTOR_FUNCTIONS_ABSPATH.'siteconfig.functions.php';

	if (!AJAXCALL) {
		//add javascript to head
		$MiniComponents->triggerEvent('00004');

		//core frontend menu items
		$MiniComponents->specificEvent('09995', 'menu', array()); //Rod really needs them
		
		//core admin menu items
		$MiniComponents->specificEvent('19995', 'menu', array());

	}

	//00005 trigger point
	$MiniComponents->triggerEvent('00005');

	if (!AJAXCALL) {
		$pageoutput = array();
		$output = array();

		//generate the cpanel menu
		$MiniComponents->specificEvent('19997', 'menu', array());
		$output[ 'CONTROL_PANEL_MENU' ] = $MiniComponents->miniComponentData[ '19997' ][ 'menu' ];

		//frequently asked questions
		$output['_CASTOR_FAQ'] = jr_gettext('_CASTOR_FAQ', '_CASTOR_FAQ', false);

		//video tutorials
		$castor_video_tutorials = castor_singleton_abstract::getInstance('castor_video_tutorials');
		$castor_video_tutorials->property_uid = 0;
		$output[ 'VIDEO_TUTORIALS' ] = $castor_video_tutorials->build_modal();
		
		//manage properties button
		$output['HMANAGE_PROPERTIES'] = jr_gettext('_CASTOR_MANAGE_PROPERTIES', '_CASTOR_MANAGE_PROPERTIES', false);

		//language dropdown
		$output[ 'LANGDROPDOWN' ] = $castor_language->get_languageselection_dropdown();



		//bootstrap
		$output[ 'USING_BOOTSTRAP' ] = 'true';

		//output top area
		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);

		if (_CASTOR_DETECTED_CMS == 'joomla3') {
			$tmpl->readTemplatesFromInput('administrator_content_area_top_vertical.html');
		} else {
			$tmpl->readTemplatesFromInput('administrator_content_area_top.html');
		}
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->displayParsedTemplate();
	}

	if (!isset( $jrConfig['initial_setup_done'])) {
		$jrConfig['initial_setup_done'] = 0;
	}

	//task
	if ( $jrConfig['initial_setup_done'] == '0' && get_showtime('task') != 'save_initial_setup') {
		$MiniComponents->specificEvent('16000','initial_setup'); // let's rock and roll
	} else {
		if ($MiniComponents->eventSpecificlyExistsCheck('16000', get_showtime('task'))) {
			$MiniComponents->specificEvent('16000', get_showtime('task')); // task exists, execute it
		} else {
			$MiniComponents->triggerEvent('10001'); //task doesn`t exist, go to cpanel frontpage
		}
	}

	//output bottom area
	if (!AJAXCALL) {
//		$pageoutput[] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		if (_CASTOR_DETECTED_CMS == 'joomla3') {
			$tmpl->readTemplatesFromInput('administrator_content_area_bottom_vertical.html');
		} else {
			$tmpl->readTemplatesFromInput('administrator_content_area_bottom.html');
		}
		$tmpl->addRows('pageoutput', array() );
		$tmpl->displayParsedTemplate();
	}

	//trigger 99994 event for webhooks
	$MiniComponents->triggerEvent('99994');

	//trigger 99998 event - castor feedback messages
	if (!AJAXCALL) {
		$MiniComponents->triggerEvent('99998');
	}

	$componentArgs = array();
	$MiniComponents->triggerEvent('99999', $componentArgs);
	
	//close/save castor session
	$tmpBookingHandler->close_castor_session();

	//done
	endrun();
} catch (Exception $e) {
	output_fatal_error($e);
}

if (defined('CASTOR_RETURNDATA')) {
	$contents = ob_get_contents();
	$contents = $head_contents.$contents;
	define('CASTOR_RETURNDATA_CONTENT', $contents);
	unset($contents);
	ob_end_clean();
} else {
	ob_end_flush();
}

// Castor 4.7.8 strips BOM from all areas of the output, not just the beginning.
function removeBOMadmin($str = '')
{
	$bom = pack('CCC', 0xef, 0xbb, 0xbf);
	$str = str_replace($bom, '', $str);

	// if(substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf))
	// {
	// $str=substr($str, 3);
	// }
	return $str;
}

