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

// ################################################################
defined('_CASTOR_INITCHECK') or die('Direct Access to this file is not allowed.');
// ################################################################
	#[AllowDynamicProperties]
class j00005advanced_micromanage_tariff_editing_modes
{
	function __construct()
	{
		$MiniComponents =castor_getSingleton('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable=false;
			return;
		}
		
		if (file_exists(get_showtime('ePointFilepath').'language'.JRDS.get_showtime('lang').'.php')) {
			require_once(get_showtime('ePointFilepath').'language'.JRDS.get_showtime('lang').'.php');
		} else {
			if (file_exists(get_showtime('ePointFilepath').'language'.JRDS.'en-GB.php')) {
				require_once(get_showtime('ePointFilepath').'language'.JRDS.'en-GB.php');
			}
		}
			
		$property_uid = getDefaultProperty();
		
		if ($property_uid > 0) {
			$mrConfig = getPropertySpecificSettings($property_uid);
			
			$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
			
			$castor_menu = castor_singleton_abstract::getInstance('castor_menu');

			if ($mrConfig[ 'is_real_estate_listing' ] != '1' && $mrConfig['tariffmode'] != '0' && !get_showtime('is_jintour_property')) {
				switch ($mrConfig['tariffmode']) {
					case '1':
						$task = 'list_tariffs_advanced';
						break;
					case '2':
						$task = 'list_tariffs_micromanage';
						break;
					case '5':
						$task = 'list_tariffs_standard';
						break;
					default:
						$task = 'list_tariffs_micromanage';
						break;
				}

					// There are some differences between J3 & J4 and the font awesome icons
					$font_awesome_tariffs = 'fa-usd';

				if (castor_bootstrap_version() == '5') {
					$font_awesome_tariffs = 'fa-dollar-sign';
				}

				if ($thisJRUser->accesslevel >= 70) {
					$castor_menu->add_item(80, jr_gettext('_CASTOR_COM_MR_LISTTARIFF_TITLE', '_CASTOR_COM_MR_LISTTARIFF_TITLE', false), $task, $font_awesome_tariffs);
				}
			}
			
			//remove the normal mode tariffs menu
			if (($mrConfig['tariffmode'] != '0' || get_showtime('is_jintour_property')) && isset($castor_menu->items['edit_tariffs_normal'])) {
				unset($castor_menu->items['edit_tariffs_normal']);
			}
		}
	}

	// This must be included in every Event/Mini-component
	function getRetVals()
	{
		return null;
	}
}

