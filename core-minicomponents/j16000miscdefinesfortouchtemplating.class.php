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

class j16000miscdefinesfortouchtemplating
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
			$this->template_touchable = true;

			return;
		}

		// On it's own, this minicomponent doesn't do anything. It's a place holder for definitions that don't have a home in any other minicomponent.
	}

	public function touch_template_language()
	{
		$output = array();

		$output[ ] = jr_gettext('_CASTOR_CLICKTOREGISTER', '_CASTOR_CLICKTOREGISTER');
		$output[ ] = jr_gettext('_CASTOR_SEARCH_PRICERANGES', '_CASTOR_SEARCH_PRICERANGES');
		$output[ ] = jr_gettext('_CASTOR_PAYPAL_REDIRECTMESSAGE', '_CASTOR_PAYPAL_REDIRECTMESSAGE');
		$output[ ] = jr_gettext('_CASTOR_PAYPAL_REDIRECTMESSAGE_IFNOTREDIRECTED', '_CASTOR_PAYPAL_REDIRECTMESSAGE_IFNOTREDIRECTED');
		$output[ ] = jr_gettext('_CASTOR_PAYPAL_REDIRECTMESSAGE_CLICKHERE', '_CASTOR_PAYPAL_REDIRECTMESSAGE_CLICKHERE');

		$output[ ] = jr_gettext('_CASTOR_WHOLEDAY_TITLE', '_CASTOR_WHOLEDAY_TITLE');
		$output[ ] = jr_gettext('_CASTOR_WHOLEDAY_DESC', '_CASTOR_WHOLEDAY_DESC');
		$output[ ] = jr_gettext('_CASTOR_FRONT_TARIFFS_PN_DAY_WHOLEDAY', '_CASTOR_FRONT_TARIFFS_PN_DAY_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_FRONT_TARIFFS_PPPN_DAY_WHOLEDAY', '_CASTOR_FRONT_TARIFFS_PPPN_DAY_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTURE_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_AJAXFORM_ACCOMMODATION_NIGHTS_WHOLEDAY', '_CASTOR_AJAXFORM_ACCOMMODATION_NIGHTS_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_BOOKINGFORM_MONITORING_DEPARTUREDATE_INVALID_WHOLEDAY', '_CASTOR_BOOKINGFORM_MONITORING_DEPARTUREDATE_INVALID_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_BOOKINGFORM_MONITORING_BOOKING_TOO_SHORT1_WHOLEDAY', '_CASTOR_BOOKINGFORM_MONITORING_BOOKING_TOO_SHORT1_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_A_MINIMUMINTERVAL_WHOLEDAY', '_CASTOR_COM_A_MINIMUMINTERVAL_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_A_MINIMUMINTERVAL_DESC_WHOLEDAY', '_CASTOR_COM_A_MINIMUMINTERVAL_DESC_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_BOOKINGFORM_PRICINGOUTPUT_WHOLEDAY', '_CASTOR_BOOKINGFORM_PRICINGOUTPUT_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_BOOKINGFORM_PRICINGOUTPUT_DAILY_WHOLEDAY', '_CASTOR_BOOKINGFORM_PRICINGOUTPUT_DAILY_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_A_DAYSBEFOREFIRSTBOOKING_WHOLEDAY', '_CASTOR_COM_A_DAYSBEFOREFIRSTBOOKING_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_A_DAYSBEFOREFIRSTBOOKING_DESC_WHOLEDAY', '_CASTOR_COM_A_DAYSBEFOREFIRSTBOOKING_DESC_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_A_ADVANCEBOOKINGSLIMITYESNO_DESC_WHOLEDAY', '_CASTOR_COM_A_ADVANCEBOOKINGSLIMITYESNO_DESC_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_A_SHOWDEPARTUREINPUT_DESC_WHOLEDAY', '_CASTOR_COM_A_SHOWDEPARTUREINPUT_DESC_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_A_FIXEDPERIODBOOKINGS_DESC_WHOLEDAY', '_CASTOR_COM_A_FIXEDPERIODBOOKINGS_DESC_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_FIXEDARRIVALDATE_YESNO_WHOLEDAY', '_CASTOR_COM_MR_FIXEDARRIVALDATE_YESNO_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_FIXEDARRIVALDATE_YESNO_DESC_WHOLEDAY', '_CASTOR_COM_MR_FIXEDARRIVALDATE_YESNO_DESC_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_FIXEDARRIVALDATE_DAY_WHOLEDAY', '_CASTOR_COM_MR_FIXEDARRIVALDATE_DAY_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_FIXEDARRIVALDATE_RECURRING_WHOLEDAY', '_CASTOR_COM_MR_FIXEDARRIVALDATE_RECURRING_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_FIXEDARRIVALDATE_RECURRING_DESC_WHOLEDAY', '_CASTOR_COM_MR_FIXEDARRIVALDATE_RECURRING_DESC_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_A_TARIFFS_PER_DESC_WHOLEDAY', '_CASTOR_COM_A_TARIFFS_PER_DESC_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_A_DEPOSIT_CHARGEDEPOSIT_VARIABLE_DESC_WHOLEDAY', '_CASTOR_COM_A_DEPOSIT_CHARGEDEPOSIT_VARIABLE_DESC_WHOLEDAY');
		$output[ ] = jr_gettext('_JOMCOMP_WISEPRICE_TITLE_DESC_FULL_WHOLEDAY', '_JOMCOMP_WISEPRICE_TITLE_DESC_FULL_WHOLEDAY');
		$output[ ] = jr_gettext('_JOMCOMP_WISEPRICE_THREASHOLD_WHOLEDAY', '_JOMCOMP_WISEPRICE_THREASHOLD_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_PENDING_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_PENDING_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVETODAY_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVETODAY_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_RESIDENT_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_RESIDENT_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTTODAY_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_DEPARTTODAY_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_STILLHERE_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_STILLHERE_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_LATE_WHOLEDAY', '_CASTOR_COM_MR_VIEWBOOKINGS_LATE_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_QUICKRES_STEP4_STAYDAYS_WHOLEDAY', '_CASTOR_COM_MR_QUICKRES_STEP4_STAYDAYS_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_FRONT_MR_MENU_ADMIN_BOOKAGUESTIN_WHOLEDAY', '_CASTOR_FRONT_MR_MENU_ADMIN_BOOKAGUESTIN_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_FRONT_MR_MENU_ADMIN_BOOKAGUESTOUT_WHOLEDAY', '_CASTOR_FRONT_MR_MENU_ADMIN_BOOKAGUESTOUT_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_FRONT_MR_BOOKIN_TITLE_WHOLEDAY', '_CASTOR_FRONT_MR_BOOKIN_TITLE_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_FRONT_MR_BOOKOUT_TITLE_WHOLEDAY', '_CASTOR_FRONT_MR_BOOKOUT_TITLE_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_INVOICE_COSTPERNIGHT_WHOLEDAY', '_CASTOR_COM_INVOICE_COSTPERNIGHT_WHOLEDAY');
		$output[ ] = jr_gettext('_CASTOR_COM_INVOICE_STAYNIGHTS_WHOLEDAY', '_CASTOR_COM_INVOICE_STAYNIGHTS_WHOLEDAY');

		$output[ ] = jr_gettext('_CASTOR_COOKIEPOLICY_1', '_CASTOR_COOKIEPOLICY_1');
		$output[ ] = jr_gettext('_CASTOR_COOKIEPOLICY_2', '_CASTOR_COOKIEPOLICY_2');
		$output[ ] = jr_gettext('_CASTOR_COOKIEPOLICY_3', '_CASTOR_COOKIEPOLICY_3');
		$output[ ] = jr_gettext('_CASTOR_COOKIEPOLICY_4', '_CASTOR_COOKIEPOLICY_4');
		$output[ ] = jr_gettext('_CASTOR_COOKIEPOLICY_5', '_CASTOR_COOKIEPOLICY_5');
		$output[ ] = jr_gettext('_CASTOR_COOKIEPOLICY_6', '_CASTOR_COOKIEPOLICY_6');

		$output[ ] = jr_gettext('COMMON_NEXT', 'COMMON_NEXT');
		$output[ ] = jr_gettext('COMMON_CANCEL', 'COMMON_CANCEL');
		$output[ ] = jr_gettext('COMMON_SUBMIT', 'COMMON_SUBMIT');
		$output[ ] = jr_gettext('COMMON_SAVE', 'COMMON_SAVE');
		$output[ ] = jr_gettext('COMMON_DELETE', 'COMMON_DELETE');
		$output[ ] = jr_gettext('COMMON_RETURN', 'COMMON_RETURN');
		$output[ ] = jr_gettext('COMMON_CLOSE', 'COMMON_CLOSE');
		$output[ ] = jr_gettext('COMMON_BACK', 'COMMON_BACK');
		$output[ ] = jr_gettext('COMMON_HOME', 'COMMON_HOME');
		$output[ ] = jr_gettext('COMMON_NEW', 'COMMON_NEW');

		//$output[]		= jr_gettext('COMMON_FULLSCREENVIEW',COMMON_FULLSCREENVIEW);
		//$output[]		= jr_gettext('COMMON_NORMALVIEW',COMMON_NORMALVIEW);

		$output[ ] = jr_gettext('COMMON_ACTION', 'COMMON_ACTION');
		$output[ ] = jr_gettext('COMMON_VIEW', 'COMMON_VIEW');
		$output[ ] = jr_gettext('COMMON_PRINT', 'COMMON_PRINT');
		$output[ ] = jr_gettext('COMMON_EDIT', 'COMMON_EDIT');
		$output[ ] = jr_gettext('COMMON_COPY', 'COMMON_COPY');

		$output[ ] = jr_gettext('_CASTOR_ALTERNATIVE_SEARCH_RESULTS', '_CASTOR_ALTERNATIVE_SEARCH_RESULTS');

		foreach ($output as $o) {
			echo $o;
			echo '<br/>';
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

