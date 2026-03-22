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
	#[AllowDynamicProperties]
	/**
	 * @package Castor\Core\Minicomponents
	 *
	 *
	 */

class j06000switch_exchange_rate
{

	/**
	 *
	 * Constructor
	 *
	 * Main functionality of the Minicomponent
	 *
	 *
	 *
	 */
	 
	public function __construct()
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}
		$tmpBookingHandler = castor_singleton_abstract::getInstance('castor_temp_booking_handler');
		$current_exchange_rate = castorGetParam($_REQUEST, 'currency_code', '');
		if ($current_exchange_rate != '') {
			$tmpBookingHandler->user_settings[ 'current_exchange_rate' ] = $current_exchange_rate;
		}
		if (!isset($tmpBookingHandler->user_settings[ 'current_exchange_rate' ])) {
			$tmpBookingHandler->user_settings[ 'current_exchange_rate' ] = 'GBP';
		}
		
		$castor_currency_conversion = castor_singleton_abstract::getInstance('castor_currency_conversion');

		if (!$castor_currency_conversion->check_currency_code_valid($tmpBookingHandler->user_settings[ 'current_exchange_rate' ])) {
			$tmpBookingHandler->user_settings[ 'current_exchange_rate' ] = 'GBP';
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

