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

class j06005show_guest_details
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
	 
	public function __construct($componentArgs)
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}
		
		jr_import('castor_encryption');
		$castor_encryption = new castor_encryption();
		
		$this->retVals = '';
		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		if (!$thisJRUser->userIsRegistered) {
			return;
		}

		$guestUid = $componentArgs[ 'guest_uid' ];
		$invoice_id = $componentArgs[ 'invoice_id' ];
		
		if ($thisJRUser->userIsManager) {
			$property_uid = getDefaultProperty();
			$query = "SELECT guests_uid FROM #__castor_guests WHERE guests_uid = '".(int) $guestUid."' AND property_uid IN (".castor_implode($thisJRUser->authorisedProperties).') ';
			$result = doSelectSql($query);
			if (count($result) < 1 || count($result) > 1) {
				throw new Exception('Unable to view guest details, either guest id not found, or guest id tampered with.');
			}
		} elseif (!$thisJRUser->is_partner) {
			$userid = $thisJRUser->id;
			$query = 'SELECT guests_uid FROM #__castor_guests WHERE `mos_userid`= '.(int) $userid.'';
			$result = doSelectSql($query);
			if (empty($result)) {
				throw new Exception('Unable to view guest details, either guest id not found, or guest id tampered with.');
			}
		} elseif ($thisJRUser->is_partner) {
			$partners = castor_singleton_abstract::getInstance('castor_partners');
			$partner_guests = $partners->get_guest_uids_for_partner($thisJRUser->id);
			if (empty($partner_guests)) {
				throw new Exception("Unable to view guest details, this partner doesn't have sufficient rights to view this guest's details.");
			}
		}

		// For 9.11 (GDPR compliance) we now need to pull from the invoice pii tables instead of the guests(guest profile) table. This gives us immutable invoice details in the event that the user chooses to delete their PII information from guests/guest_profile table, allowing us to be compliant with both the GDPR Right to be Forgotten rules, and various country rules that would demand that invoice details be retrievable for the forseeable future.
		
		jr_import('jrportal_invoice_pii_details');
		$jrportal_invoice_pii_details = new jrportal_invoice_pii_details();
		$jrportal_invoice_pii_details->invoice_id=$invoice_id;
		$guestData = $jrportal_invoice_pii_details->get_pii_buyer();

		$output[ 'FIRSTNAME' ] = $guestData['firstname'];
		$output[ 'SURNAME' ] = $guestData['surname'];
		$output[ 'HOUSE' ] = $guestData['house'];
		$output[ 'STREET' ] = $guestData['street'];
		$output[ 'TOWN' ] = $guestData['town'];
		$output[ 'REGION' ] = find_region_name($guestData['county']);
		$output[ 'COUNTRY' ] = getSimpleCountry($guestData['country']);
		$output[ 'POSTCODE' ] = $guestData['postcode'];
		$output[ 'LANDLINE' ] = $guestData['tel_landline'];
		$output[ 'MOBILE' ] = $guestData['tel_mobile'];
		$output[ 'EMAIL' ] = $guestData['email'];
		$vat_output[0][ 'VAT_NUMBER' ] = $guestData['vat_number'];

		$output[ 'TITLE' ] = jr_gettext('_CASTOR_COM_MR_EDITBOOKING_TAB_GUEST', '_CASTOR_COM_MR_EDITBOOKING_TAB_GUEST');
		$output[ 'HFIRSTNAME' ] = jr_gettext('_CASTOR_COM_MR_VIEWBOOKINGS_SURNAME', '_CASTOR_COM_MR_VIEWBOOKINGS_SURNAME');
		$output[ 'HSURNAME' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_SURNAME', '_CASTOR_COM_MR_DISPGUEST_SURNAME');
		$output[ 'HHOUSE' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_HOUSE', '_CASTOR_COM_MR_DISPGUEST_HOUSE');
		$output[ 'HSTREET' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_STREET', '_CASTOR_COM_MR_DISPGUEST_STREET');
		$output[ 'HTOWN' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_TOWN', '_CASTOR_COM_MR_DISPGUEST_TOWN');
		$output[ 'HREGION' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION');
		$output[ 'HCOUNTRY' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY');
		$output[ 'HPOSTCODE' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_POSTCODE', '_CASTOR_COM_MR_DISPGUEST_POSTCODE');
		$output[ 'HLANDLINE' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_LANDLINE', '_CASTOR_COM_MR_DISPGUEST_LANDLINE');
		$output[ 'HMOBILE' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_MOBILE', '_CASTOR_COM_MR_DISPGUEST_MOBILE');
		$output[ 'HFAX' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_FAX', '_CASTOR_COM_MR_DISPGUEST_FAX');
		$output[ 'HEMAIL' ] = jr_gettext('_CASTOR_COM_MR_EB_GUEST_CASTOR_EMAIL_EXPL', '_CASTOR_COM_MR_EB_GUEST_CASTOR_EMAIL_EXPL');
		$vat_output[0][ '_CASTOR_COM_YOURBUSINESS_VATNO' ] = jr_gettext('_CASTOR_COM_YOURBUSINESS_VATNO', '_CASTOR_COM_YOURBUSINESS_VATNO');

		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_FRONTEND);
		$tmpl->readTemplatesFromInput('show_guest_details.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		if (trim($vat_output[0][ 'VAT_NUMBER' ]) != '') {
			$tmpl->addRows('vat_output', $vat_output);
		}
		$this->retVals = $tmpl->getParsedTemplate();
	}


	public function getRetVals()
	{
		return $this->retVals;
	}
}

