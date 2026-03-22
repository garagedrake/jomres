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

class j06005show_manager_details
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
		
		$manager_profile_id = $componentArgs[ 'manager_profile_id' ];
		$invoice_id = $componentArgs[ 'invoice_id' ];
		
		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		if (!$thisJRUser->userIsRegistered) {
			return;
		}

		if ($manager_profile_id == 0) {
			return false;
		}

		if (!$thisJRUser->superPropertyManager && $thisJRUser->id != $manager_profile_id) { // The user's not a super property manager, and this invoice isn't for their user id
			return false;
		}

		$query = 'SELECT enc_firstname,enc_surname,enc_house,enc_street,enc_town,enc_county,enc_country,enc_postcode,enc_tel_landline,enc_tel_mobile,enc_email,enc_vat_number FROM #__castor_guest_profile WHERE cms_user_id = '.(int) $manager_profile_id.'';
		$managerData = doSelectSql($query);

		$numberOfReturns = count($managerData);
		$vat_output = array();
		if ($numberOfReturns > 0) {
			foreach ($managerData as $data) {
				$output[ 'FIRSTNAME' ] = $castor_encryption->decrypt($data->enc_firstname);
				$output[ 'SURNAME' ] = $castor_encryption->decrypt($data->enc_surname);
				$output[ 'HOUSE' ] = $castor_encryption->decrypt($data->enc_house);
				$output[ 'STREET' ] = $castor_encryption->decrypt($data->enc_street);
				$output[ 'TOWN' ] = $castor_encryption->decrypt($data->enc_town);
				$output[ 'REGION' ] = find_region_name($castor_encryption->decrypt($data->enc_county));
				$output[ 'COUNTRY' ] = getSimpleCountry($castor_encryption->decrypt($data->enc_country));
				$output[ 'POSTCODE' ] = $castor_encryption->decrypt($data->enc_postcode);
				$output[ 'LANDLINE' ] = $castor_encryption->decrypt($data->enc_tel_landline);
				$output[ 'MOBILE' ] = $castor_encryption->decrypt($data->enc_tel_mobile);
				$output[ 'EMAIL' ] = $castor_encryption->decrypt($data->enc_email);
				$vat_output[0][ 'VAT_NUMBER' ] = $castor_encryption->decrypt($data->enc_vat_number);
			}
		} else {
			return false;
		}
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

