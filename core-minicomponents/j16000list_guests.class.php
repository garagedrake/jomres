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

class j16000list_guests
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
	 
	function __construct()
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;
			return;
		}

		jr_import('castor_encryption');
		$this->castor_encryption = new castor_encryption();
		
		jr_import("castor_properties");
		$properties = new castor_properties();
		$properties->get_all_properties();
		$property_uids = $properties->all_property_uids;
		
		$basic_property_details = castor_singleton_abstract::getInstance('basic_property_details');
		$basic_property_details->get_property_name_multi($property_uids['all_propertys']);

		$output	 = array ();
		$rows	   = array ();
		$pageoutput = array ();

		$output[ 'PAGETITLE' ] = jr_gettext('_CASTOR_HLIST_GUESTS', '_CASTOR_HLIST_GUESTS', false);
		$output[ '_CASTOR_GDPR_RTBF_ANONYMISE_GUEST_INTRO' ] = jr_gettext('_CASTOR_GDPR_RTBF_ANONYMISE_GUEST_INTRO', '_CASTOR_GDPR_RTBF_ANONYMISE_GUEST_INTRO', false);
		
		$output[ 'HPROPERTYNAME' ] = jr_gettext('_CASTOR_SORTORDER_PROPERTYNAME', '_CASTOR_SORTORDER_PROPERTYNAME', false);
		$output[ 'HFIRSTNAME' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_FIRSTNAME', '_CASTOR_COM_MR_DISPGUEST_FIRSTNAME', false);
		$output[ 'HSURNAME' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_SURNAME', '_CASTOR_COM_MR_DISPGUEST_SURNAME', false);
		$output[ 'HHOUSE' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_HOUSE', '_CASTOR_COM_MR_DISPGUEST_HOUSE', false);
		$output[ 'HSTREET' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_STREET', '_CASTOR_COM_MR_DISPGUEST_STREET', false);
		$output[ 'HTOWN' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_TOWN', '_CASTOR_COM_MR_DISPGUEST_TOWN', false);
		$output[ 'HREGION' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION', false);
		$output[ 'HPOSTCODE' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_POSTCODE', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_POSTCODE', false);
		$output[ 'HCOUNTRY' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY', false);
		$output[ 'HLANDLINE' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_LANDLINE', '_CASTOR_COM_MR_DISPGUEST_LANDLINE', false);
		$output[ 'HMOBILE' ] = jr_gettext('_CASTOR_COM_MR_DISPGUEST_MOBILE', '_CASTOR_COM_MR_DISPGUEST_MOBILE', false);
		$output[ 'HEMAIL' ] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_EMAIL', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_EMAIL', false);
		$output[ 'HVAT' ] = jr_gettext('_CASTOR_COM_YOURBUSINESS_VATNO', '_CASTOR_COM_YOURBUSINESS_VATNO', false);

		$query = "SELECT
			guests_uid,
			enc_firstname,
			enc_surname,
			enc_house,
			enc_street,
			enc_town,
			enc_county,
			enc_country,
			enc_postcode,
			enc_tel_landline,
			enc_tel_mobile,
			enc_email,
			enc_vat_number,
			property_uid
			FROM
			#__castor_guests";
			
		$result = doSelectSql($query);

		foreach ($result as $g) {
			$r = array ();

			$r['FIRSTNAME'] = $this->castor_encryption->decrypt($g->enc_firstname);
			$r['SURNAME'] = $this->castor_encryption->decrypt($g->enc_surname);
			$r['HOUSE'] = $this->castor_encryption->decrypt($g->enc_house);
			$r['STREET'] = $this->castor_encryption->decrypt($g->enc_street);
			$r['TOWN'] = $this->castor_encryption->decrypt($g->enc_town);
			$r['COUNTY'] = castor_decode(find_region_name($this->castor_encryption->decrypt($g->enc_county)));
			$r['POSTCODE'] = $this->castor_encryption->decrypt($g->enc_postcode);
			$r['COUNTRY'] = $this->castor_encryption->decrypt($g->enc_country);
			$r['TEL_LANDLINE'] = $this->castor_encryption->decrypt($g->enc_tel_landline);
			$r['TEL_MOBILE'] = $this->castor_encryption->decrypt($g->enc_tel_mobile);
			$r['EMAIL'] = $this->castor_encryption->decrypt($g->enc_email);
			$r['VAT_NUMBER'] = $this->castor_encryption->decrypt($g->enc_vat_number);

			if (isset($basic_property_details->property_names[$g->property_uid])) {
				$r['PROPERTY_NAME'] = $basic_property_details->property_names[$g->property_uid];
			} else {
				$r['PROPERTY_NAME'] = jr_gettext('_CASTOR_GDPR_RTBF_UNKNOWN_PROPERTY', '_CASTOR_GDPR_RTBF_UNKNOWN_PROPERTY', false);
			}
			
			if ($g->property_uid > 0) {
				$toolbar = castor_singleton_abstract::getInstance('castorItemToolbar');
				$toolbar->newToolbar();
				$toolbar->addItem('fa fa-pencil-square-o', 'btn btn-info', '', castorURL(CASTOR_SITEPAGE_URL_ADMIN . '&task=anonymise_guest&guest_id=' . $g->guests_uid.'&property_uid='.$g->property_uid), jr_gettext('_CASTOR_GDPR_RTBF_ANONYMISE_GUEST', '_CASTOR_GDPR_RTBF_ANONYMISE_GUEST', false));
				$r['LINKTEXT'] = $toolbar->getToolbar();
			} else {
				$r['LINKTEXT'] = jr_gettext('_CASTOR_GDPR_RTBF_GUEST_CANNOT_REDACT', '_CASTOR_GDPR_RTBF_GUEST_CANNOT_REDACT', false);
			}

			$rows[] = $r;
		}

		$jrtbar = castor_singleton_abstract::getInstance('castor_toolbar');
		$jrtb  = $jrtbar->startTable();
		$jrtb .= $jrtbar->toolbarItem('cancel', CASTOR_SITEPAGE_URL_ADMIN, jr_gettext("COMMON_CANCEL", 'COMMON_CANCEL', false));
		$jrtb .= $jrtbar->endTable();
		$output['CASTORTOOLBAR']=$jrtb;

		$pageoutput[] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->readTemplatesFromInput('list_guests.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->addRows('rows', $rows);
		$tmpl->displayParsedTemplate();
	}


	function getRetVals()
	{
		return null;
	}
}

