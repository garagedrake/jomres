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

class j06001confirmation_letter
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

		$email_type = 'email_guest_confirmationletter';

		$property_uid = getDefaultProperty();
		$mrConfig = getPropertySpecificSettings();
		$contract_uid = castorGetParam($_REQUEST, 'contract_uid', 0);
		$sendemail = castorGetParam($_REQUEST, 'sendemail', 0);

		$booking_email_details = castor_singleton_abstract::getInstance('castor_generic_booking_email');

		if ($sendemail == 1) {
			$booking_email_details->gather_data($contract_uid, $property_uid, $print = false);
		} else {
			$booking_email_details->gather_data($contract_uid, $property_uid, $print = true);
		}

		$booking_email_details->parse_email($email_type, $contract_uid);

		if ($sendemail == 1) {
			if (!castorMailer(
				$booking_email_details->data[$contract_uid]['PROPERTY_EMAIL'],
				$booking_email_details->data[$contract_uid]['PROPERTY_NAME'],
				$booking_email_details->data[$contract_uid]['EMAIL'],
				$booking_email_details->parsed_email['subject'],
				$booking_email_details->parsed_email['text'],
				$mode = 1,
				$booking_email_details->parsed_email['attachments']
			)
				) {
				error_logging('Failure in sending confirmation letter to guest. Target address: '.$booking_email_details->data[$contract_uid]['EMAIL'].' Subject '.$booking_email_details->parsed_email['subject'].$booking_email_details->parsed_email['text']);
			} else {
				echo jr_gettext('_CASTOR_CONFIRMATION_EMAIL_SENT', '_CASTOR_CONFIRMATION_EMAIL_SENT', false);
			}
		} else {
			echo $booking_email_details->parsed_email['text'];
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

