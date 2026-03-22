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

class j06005save_new_property
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

		$castor_gdpr_optin_consent = new castor_gdpr_optin_consent();
		if (!$castor_gdpr_optin_consent->user_consents_to_storage()) {
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=opted_out&jr_redirect_url='.getCurrentUrl()), '');
		}

		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');

		if ($jrConfig['selfRegistrationAllowed'] == '0' && !$thisJRUser->superPropertyManager) {
			return;
		}

		$property_name = trim(castorGetParam($_POST, 'property_name', ''));
		if ($property_name == '') {
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=new_property'), '');

			return;
		}

		$max_occupancy = (int)castorGetParam($_POST, 'max_occupancy', 0);

		//castor properties object
		$castor_properties = castor_singleton_abstract::getInstance('castor_properties');

		$castor_properties->property_name = $property_name;

		if ($jrConfig['limit_property_country'] == '0') {
			$castor_properties->property_country = castorGetParam($_POST, 'new_property_country', '');
		} else {
			$castor_properties->property_country = $jrConfig['limit_property_country_country'];
		}

		$castor_properties->property_region = castorGetParam($_POST, 'region', '');
		$castor_properties->property_email = castorGetParam($_POST, 'property_email', '');
		$castor_properties->property_site_id = castorGetParam($_POST, 'property_site_id', '');
		$castor_properties->ptype_id = (int) castorGetParam($_POST, 'propertyType', 0);
		$castor_properties->property_key = str_replace(',', '', castorGetParam($_POST, 'price', ''));
		$castor_properties->max_occupancy = $max_occupancy;

		//insert new property
		$castor_properties->commit_new_property();

		$castor_messaging = castor_singleton_abstract::getInstance('castor_messages');
		$castor_messaging->set_message(jr_gettext('_CASTOR_REGISTRATION_AUDIT_CREATEPROPERTY', '_CASTOR_REGISTRATION_AUDIT_CREATEPROPERTY', false));

		//04901 trigger point (assign a default commission rate to the new property, new property welcome email, etc)
		$componentArgs = array('property_uid' => $castor_properties->propertys_uid);
		$MiniComponents->triggerEvent('04901', $componentArgs);

		//send admin email if the new property requires approval. TODO: move to 04901 trigger point too
		if ($castor_properties->approved == 0) {
			$link = CASTOR_SITEPAGE_URL_ADMIN.'&task=property_approvals';
			$subject = jr_gettext('_CASTOR_APPROVALS_ADMIN_EMAIL_SUBJECT', '_CASTOR_APPROVALS_ADMIN_EMAIL_SUBJECT', false);
			$message = jr_gettext('_CASTOR_APPROVALS_ADMIN_EMAIL_CONTENT', '_CASTOR_APPROVALS_ADMIN_EMAIL_CONTENT', false).$link;
			sendAdminEmail($subject, $message);
		}

		castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&thisProperty='.$castor_properties->propertys_uid), '');
	}


	public function getRetVals()
	{
		return null;
	}
}

