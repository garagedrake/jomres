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
defined('_CASTOR_INITCHECK') or die('');
// ################################################################
	
	/**
	 *
	 * @package Castor\Core\Classes
	 *
	 */
	#[AllowDynamicProperties]
class castor_editing_mode
{

	/**
	 *
	 *
	 *
	 */

	public function __construct()
	{
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		
		$tmpBookingHandler = castor_singleton_abstract::getInstance('castor_temp_booking_handler');

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		
		$this->editing_allowed = true;
		
		if (!isset($tmpBookingHandler->user_settings[ 'editing_on' ])) {
			$tmpBookingHandler->user_settings[ 'editing_on' ] = false;
		}
		
		if (!$thisJRUser->userIsManager) {
			$this->editing_allowed = false;
			$tmpBookingHandler->user_settings[ 'editing_on' ] = false;
		}
		
		if ($thisJRUser->userIsManager && $thisJRUser->accesslevel < 70) { //lower than manager
			$this->editing_allowed = false;
			$tmpBookingHandler->user_settings[ 'editing_on' ] = false;
		}
		
		if ($jrConfig[ 'editingModeAffectsAllProperties' ] == '1' && $thisJRUser->superPropertyManager) {
			$this->editing_allowed = true;
			$tmpBookingHandler->user_settings[ 'editing_on' ] = true;
		}
		
		$this->editing = $tmpBookingHandler->user_settings[ 'editing_on' ];
	}
	
	/**
	 *
	 *
	 *
	 */

	public function switch_mode_on()
	{
		if (!$this->editing_allowed) {
			return false;
		}
		$tmpBookingHandler = castor_singleton_abstract::getInstance('castor_temp_booking_handler');
		$tmpBookingHandler->user_settings[ 'editing_on' ] = true;
		
		$this->editing = true;
	}
	
	/**
	 *
	 *
	 *
	 */

	public function switch_mode_off()
	{
		if (!$this->editing_allowed) {
			return false;
		}
		$tmpBookingHandler = castor_singleton_abstract::getInstance('castor_temp_booking_handler');
		$tmpBookingHandler->user_settings[ 'editing_on' ] = false;
		
		$this->editing = false;
	}
	
	/**
	 *
	 *
	 *
	 */

	public function make_editing_mode_dropdown()
	{
		if (!$this->editing_allowed) {
			return false;
		}
		$on_text = jr_gettext('_CASTOR_EDITINGMODE_ON', '_CASTOR_EDITINGMODE_ON', false);
		$off_text = jr_gettext('_CASTOR_EDITINGMODE_OFF', '_CASTOR_EDITINGMODE_OFF', false);

		$mode_options = array();
		$mode_options[ ] = castorHTML::makeOption('0', $off_text);
		$mode_options[ ] = castorHTML::makeOption('1', $on_text);
		$javascript = 'onchange="switch_editing_mode(\''.CASTOR_SITEPAGE_URL_AJAX.'\',this.value);"';

		return castorHTML::selectList($mode_options, 'castor_editing_mode', ' autocomplete="off" '.$javascript.'', 'value', 'text', $this->editing);
	}
}

