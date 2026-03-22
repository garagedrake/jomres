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
	/**
	 * @package Castor\Core\Minicomponents
	 *
	 *
	 */

class j06005new_property
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
			$this->shortcode_data = array(
				'task' => 'new_property',
				'info' => '_CASTOR_SHORTCODES_06005NEW_PROPERTY',
				'arguments' => array()
				);
			return;
		}
		castor_cmsspecific_setmetadata('title', castor_purify_html(jr_gettext('_CASTOR_USER_LISTMYPROPERTY', '_CASTOR_USER_LISTMYPROPERTY', false)));
		
		$castor_gdpr_optin_consent = new castor_gdpr_optin_consent();
		if (!$castor_gdpr_optin_consent->user_consents_to_storage()&& !isset($_REQUEST['skip_consent_form'])) {
			echo $consent_form = $MiniComponents->specificEvent('06000', 'show_consent_form', array ('output_now' => false));
			return;
		}

		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		
		$castor_property_types = castor_singleton_abstract::getInstance('castor_property_types');

		if ($jrConfig['selfRegistrationAllowed'] == '0' && !$thisJRUser->superPropertyManager) {
			return;
		}
		
		// You can, by all means, remove this section of code but it's only here to ensure you don't lock yourself out of your own system accidentally
		if (function_exists("get_number_of_allowed_properties")) {
			if (get_showtime('numberOfPropertiesInSystem') >= get_number_of_allowed_properties()) {
				echo '<p class="alert alert-danger">Error, your license does not allow you to add more properties</p>';
				return;
			}
		}

			
		//get selected country
		$selectedCountry = castorGetParam($_REQUEST, 'new_property_country', '');
		if ($selectedCountry == '') {
			$selectedCountry = $jrConfig['limit_property_country_country'];
		}

		$output = array();
		$propertyRegion = '';

		//setup countries and regions dropdowns
		if ($jrConfig['limit_property_country'] == '0') {
			$output['REGIONDROPDOWN'] = setupRegions($selectedCountry, $propertyRegion);
			$output['COUNTRIESDROPDOWN'] = createCountriesDropdown($selectedCountry, 'new_property_country', false);
		} else {
			$output['REGIONDROPDOWN'] = setupRegions($jrConfig['limit_property_country_country'], $propertyRegion);
			$output['COUNTRIESDROPDOWN'] = getSimpleCountry($jrConfig['limit_property_country_country']);
		}

		$output['PAGETITLE'] = jr_gettext('_CASTOR_COM_MR_NEWPROPERTY', '_CASTOR_COM_MR_NEWPROPERTY', false);
		$output['HCOUNTRY'] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_COUNTRY', false);
		$output['HREGION'] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_REGION', false);
		$output['HNAME'] = jr_gettext('_JRPORTAL_PROPERTIES_PROPERTYNAME', '_JRPORTAL_PROPERTIES_PROPERTYNAME', false);
		$output['HEMAIL'] = jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_EMAIL', '_CASTOR_COM_MR_VRCT_PROPERTY_HEADER_EMAIL', false);
		$output['_CASTOR_FIELDS_HIGHLIGHTED_ARE_REQUIRED'] = jr_gettext('_CASTOR_FIELDS_HIGHLIGHTED_ARE_REQUIRED', '_CASTOR_FIELDS_HIGHLIGHTED_ARE_REQUIRED', false);

		$output['CASTOR_PROPERTY_REGISTRATION_INSTRUCTIONS_TITLE'] = jr_gettext('CASTOR_PROPERTY_REGISTRATION_INSTRUCTIONS_TITLE', 'CASTOR_PROPERTY_REGISTRATION_INSTRUCTIONS_TITLE', false);
		$output['CASTOR_PROPERTY_REGISTRATION_INSTRUCTIONS_NOTE1'] = jr_gettext('CASTOR_PROPERTY_REGISTRATION_INSTRUCTIONS_NOTE1', 'CASTOR_PROPERTY_REGISTRATION_INSTRUCTIONS_NOTE1', false);
		$output['CASTOR_PROPERTY_REGISTRATION_INSTRUCTIONS_NOTE2'] = jr_gettext('CASTOR_PROPERTY_REGISTRATION_INSTRUCTIONS_NOTE2', 'CASTOR_PROPERTY_REGISTRATION_INSTRUCTIONS_NOTE2', false);

		$output['DROPDOWN_MAX_OCCUPANCY'] = castorHTML::integerSelectList(1, 1000, 1, 'max_occupancy', '', 6);
		$output['CASTOR_OCCUPANCY_LEVELS_MAX_OCCUPANCY'] = jr_gettext('CASTOR_OCCUPANCY_LEVELS_MAX_OCCUPANCY', 'CASTOR_OCCUPANCY_LEVELS_MAX_OCCUPANCY', false);

		$output['HPROPERTY_TYPE'] = jr_gettext('_CASTOR_FRONT_PTYPE', '_CASTOR_FRONT_PTYPE', false);
		$output['PROPERTY_TYPE_DROPDOWN'] = $castor_property_types->getPropertyTypeDropdown('', true);

		$pageoutput[] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(CASTOR_TEMPLATEPATH_BACKEND);
		$tmpl->readTemplatesFromInput('new_property.html');
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->displayParsedTemplate();
	}


	public function getRetVals()
	{
		return null;
	}
}

