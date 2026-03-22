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

class j06001cancel_booking
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
		$mrConfig = getPropertySpecificSettings();
		$contract_uid = castorGetParam($_REQUEST, 'contract_uid', 0);

		$jsLink = castorURL(CASTOR_SITEPAGE_URL."&task=save_cancellation&contract_uid=$contract_uid");
		$defaultProperty = getDefaultProperty();
		$today = date('Y/m/d');

		$current_contract_details = castor_singleton_abstract::getInstance('basic_contract_details');
		$current_contract_details->gather_data($contract_uid, $defaultProperty);
	
		if (isset($current_contract_details->contract[$contract_uid])) {
				$arrival = $current_contract_details->contract[$contract_uid]['contractdeets']['arrival'];
				$deposit_paid = $current_contract_details->contract[$contract_uid]['contractdeets']['deposit_paid'];
				$contract_total = $current_contract_details->contract[$contract_uid]['contractdeets']['contract_total'];
				$deposit_required = $current_contract_details->contract[$contract_uid]['contractdeets']['deposit_required'];
				$booked_in = $current_contract_details->contract[$contract_uid]['contractdeets']['booked_in'];
				$property_uid = (int) $defaultProperty;
			
			if ($booked_in != '1') {
				$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_CANCELBOOKING', '_CASTOR_COM_MR_EB_GUEST_CASTOR_CANCELBOOKING');
				$output[ 'SAVEBUTTON' ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CANCELLATION_BUTTON', '_CASTOR_COM_MR_EB_GUEST_CANCELLATION_BUTTON', false);
				$output[ 'HREASON' ] = jr_gettext('_CASTOR_JR_BLACKBOOKING_REASON', '_CASTOR_JR_BLACKBOOKING_REASON');
				
				$output[ 'BOOKING_NUMBER' ] = $current_contract_details->contract[$contract_uid]['contractdeets']['tag'];
				$output[ 'GUEST_NAME' ] = $current_contract_details->contract[$contract_uid]['guestdeets']['firstname']." ".$current_contract_details->contract[$contract_uid]['guestdeets']['surname'];
				
				$output[ 'CONTRACT_UID' ] = $contract_uid;
				$output[ 'HARRIVAL' ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL', '_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL');
				$output[ 'HCONTRACTTOTAL' ] = jr_gettext('_CASTOR_COM_MR_EB_PAYM_CONTRACT_TOTAL', '_CASTOR_COM_MR_EB_PAYM_CONTRACT_TOTAL');
				$output[ 'HDAYSTOARRIVAL' ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_DAYSTOARRIVAL', '_CASTOR_COM_MR_EB_GUEST_DAYSTOARRIVAL');

				$output[ 'ARRIVAL' ] = outputDate($arrival);
				$output[ 'CONTRACTTOTAL' ] = output_price($contract_total);
				$output[ 'DAYSTOARRIVAL' ] = dateDiff('d', $today, $arrival);

				if ($deposit_paid == '1') {
					$output[ 'HDEPOSITPAID' ] = jr_gettext('_CASTOR_COM_MR_EB_PAYM_DEPOSIT_PAID', '_CASTOR_COM_MR_EB_PAYM_DEPOSIT_PAID');
					$output[ 'DEPOSITAMOUNT' ] = output_price($deposit_required);
				} else {
					$output[ 'HDEPOSITPAID' ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CANCELLATION_NODEPOSIT', '_CASTOR_COM_MR_EB_GUEST_CANCELLATION_NODEPOSIT');
					$output[ 'DEPOSITAMOUNT' ] = '';
				}

				$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
				$jrtb = $jrtbar->startTable();

				$jrtb .= $jrtbar->toolbarItem('cancel', castorURL(CASTOR_SITEPAGE_URL.'&task=edit_booking&contract_uid='.$contract_uid), '');
				$jrtb .= $jrtbar->toolbarItem('save', '', '', true, 'save_cancellation');
				$jrtb .= $jrtbar->endTable();
				$output[ 'CASTORTOOLBAR' ] = $jrtb;

				$pageoutput[ ] = $output;

				$tmpl = new patTemplate();
				$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
				$tmpl->readTemplatesFromInput('cancel_booking.html');
				$tmpl->addRows('pageoutput', $pageoutput);
				$tmpl->displayParsedTemplate();
				$status = 'status=no,toolbar=yes,scrollbars=yes,titlebar=yes,menubar=yes,resizable=yes,width=710,height=710,directories=no,location=no';
				$link = CASTOR_SITEPAGE_URL.'&task=invoiceForm&contract_uid='.$contract_uid;
			} else {
				echo jr_gettext('_CASTOR_COM_MR_EB_GUEST_CANCELLATION_ALREADYBOOKEDIN', '_CASTOR_COM_MR_EB_GUEST_CANCELLATION_ALREADYBOOKEDIN');
			}
		} else {
			trigger_error('Error when cancelling booking, incorrect contract uid used (hack attempt?)', E_USER_ERROR);
		}
	}

	public function touch_template_language()
	{
		$output = array();

		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CANCELLATION_ALREADYBOOKEDIN', '_CASTOR_COM_MR_EB_GUEST_CANCELLATION_ALREADYBOOKEDIN');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CANCELLATION_NODEPOSIT', '_CASTOR_COM_MR_EB_GUEST_CANCELLATION_NODEPOSIT');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_PAYM_DEPOSIT_PAID', '_CASTOR_COM_MR_EB_PAYM_DEPOSIT_PAID');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL', '_CASTOR_COM_MR_VIEWBOOKINGS_ARRIVAL');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_PAYM_CONTRACT_TOTAL', '_CASTOR_COM_MR_EB_PAYM_CONTRACT_TOTAL');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_DAYSTOARRIVAL', '_CASTOR_COM_MR_EB_GUEST_DAYSTOARRIVAL');
		$output[ ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CANCELLATION_BUTTON', '_CASTOR_COM_MR_EB_GUEST_CANCELLATION_BUTTON');

		foreach ($output as $o) {
			echo $o;
			echo '<br/>';
		}
	}

/**
 * Must be included in every mini-component.
 #
 * Returns any settings that the mini-component wants to send back to the calling script. In addition to being returned to the calling script they are put into an array in the mcHandler object as eg. $mcHandler->miniComponentData[$ePoint][$eName]
 */

	public function getRetVals()
	{
		return null;
	}
}

