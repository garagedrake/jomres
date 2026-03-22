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

class j06002save_property
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

		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');

		$property_uid = intval(castorGetParam($_POST, 'property_uid', 0));

		if ($property_uid > 0 && !in_array($property_uid, $thisJRUser->authorisedProperties)) {
			$property_uid = getDefaultProperty();
		}

		if ($jrConfig[ 'selfRegistrationAllowed' ] == '0' && $property_uid == 0) {
			$property_uid = getDefaultProperty();
		}
		
		$published = 0;
		$approved = 0;
		
		//castor properties object
		$castor_properties = castor_singleton_abstract::getInstance('castor_properties');
		
		//get property details
		if ($property_uid > 0) {
			$current_property_details = castor_singleton_abstract::getInstance('basic_property_details');
			$current_property_details->gather_data($property_uid);
			
			$published = $current_property_details->published;
			$approved = $current_property_details->approved;
		}

		$castor_properties->propertys_uid = $property_uid;
		$castor_properties->property_name = trim(castorGetParam($_POST, 'property_name', ''));
		$castor_properties->property_street = castorGetParam($_POST, 'property_street', '');
		$castor_properties->property_town = castorGetParam($_POST, 'property_town', '');
		$castor_properties->property_region = castorGetParam($_POST, 'region', '');
		$castor_properties->property_postcode = castorGetParam($_POST, 'property_postcode', '');
		$castor_properties->property_tel = castorGetParam($_POST, 'property_tel', '');
		$castor_properties->property_fax = castorGetParam($_POST, 'property_fax', '');
		$castor_properties->property_email = castorGetParam($_POST, 'property_email', '');
		$castor_properties->metatitle = castorGetParam($_POST, 'metatitle', '');
		$castor_properties->metadescription = castorGetParam($_POST, 'metadescription', '');
		$castor_properties->metakeywords = castorGetParam($_POST, 'metakeywords', '');
		$castor_properties->price = convert_entered_price_into_safe_float(castorGetParam($_POST, 'price', ''));
		$castor_properties->lat = castorGetParam($_POST, 'lat', '');
		$castor_properties->long = castorGetParam($_POST, 'long', '');
		$castor_properties->property_site_id = castorGetParam($_POST, 'property_site_id', '');
		$castor_properties->ptype_id = castorGetParam($_POST, 'propertyType', 0);
		$castor_properties->stars = castorGetParam($_POST, 'stars', 0);
		$castor_properties->superior = castorGetParam($_POST, 'superior', 0);
		$castor_properties->cat_id = castorGetParam($_POST, 'cat_id', 0);
		$castor_properties->permit_number = castorGetParam($_POST, 'permit_number', '');
		$castor_properties->property_features = castorGetParam($_POST, 'pid', array());
		$castor_properties->published = $published;
		$castor_properties->approved = $approved;

		//property country
		if ($jrConfig[ 'limit_property_country' ] == '0') {
			$castor_properties->property_country = castorGetParam($_POST, 'country', '');
		} else {
			$castor_properties->property_country = $jrConfig[ 'limit_property_country_country' ];
		}

		//html editor fields
		if ($jrConfig[ 'allowHTMLeditor' ] == '0') {
			$property_description = $this->convert_lessgreaterthans(castorGetParam($_POST, 'property_description', ''));
			$property_checkin_times = $this->convert_lessgreaterthans(castorGetParam($_POST, 'property_checkin_times', ''));
			$property_area_activities = $this->convert_lessgreaterthans(castorGetParam($_POST, 'property_area_activities', ''));
			$property_driving_directions = $this->convert_lessgreaterthans(castorGetParam($_POST, 'property_driving_directions', ''));
			$property_airports = $this->convert_lessgreaterthans(castorGetParam($_POST, 'property_airports', ''));
			$property_othertransport = $this->convert_lessgreaterthans(castorGetParam($_POST, 'property_othertransport', ''));
			$property_policies_disclaimers = $this->convert_lessgreaterthans(castorGetParam($_POST, 'property_policies_disclaimers', ''));

			$castor_properties->property_description = strip_tags($property_description, '<p><br>');
			$castor_properties->property_checkin_times = strip_tags($property_checkin_times, '<p><br>');
			$castor_properties->property_area_activities = strip_tags($property_area_activities, '<p><br>');
			$castor_properties->property_driving_directions = strip_tags($property_driving_directions, '<p><br>');
			$castor_properties->property_airports = strip_tags($property_airports, '<p><br>');
			$castor_properties->property_othertransport = strip_tags($property_othertransport, '<p><br>');
			$castor_properties->property_policies_disclaimers = strip_tags($property_policies_disclaimers, '<p><br>');
		} else {
			$castor_properties->property_description = castorGetParam($_POST, 'property_description', '');
			$castor_properties->property_checkin_times = castorGetParam($_POST, 'property_checkin_times', '');
			$castor_properties->property_area_activities = castorGetParam($_POST, 'property_area_activities', '');
			$castor_properties->property_driving_directions = castorGetParam($_POST, 'property_driving_directions', '');
			$castor_properties->property_airports = castorGetParam($_POST, 'property_airports', '');
			$castor_properties->property_othertransport = castorGetParam($_POST, 'property_othertransport', '');
			$castor_properties->property_policies_disclaimers = castorGetParam($_POST, 'property_policies_disclaimers', '');
		}

		//insert new property
		$castor_properties->commit_update_property();

		//save message
		$castor_messaging = castor_singleton_abstract::getInstance('castor_messages');
		$castor_messaging->set_message(jr_gettext('_CASTOR_COM_MR_VRCT_PROPERTY_SAVE_UPDATE', '_CASTOR_COM_MR_VRCT_PROPERTY_SAVE_UPDATE', false));

		//send approval email to site admin
		if ((int) $jrConfig['automatically_approve_new_properties'] == 0 && !$thisJRUser->superPropertyManager) {
			$link = CASTOR_SITEPAGE_URL_ADMIN.'&task=property_approvals';
			$subject = jr_gettext('_CASTOR_APPROVALS_ADMIN_EMAIL_SUBJECT', '_CASTOR_APPROVALS_ADMIN_EMAIL_SUBJECT', false).' ('.$castor_properties->property_name.') ';
			$message = jr_gettext('_CASTOR_APPROVALS_ADMIN_EMAIL_CONTENT', '_CASTOR_APPROVALS_ADMIN_EMAIL_CONTENT', false).$link;
			sendAdminEmail($subject, $message);
		}

		//04902 trigger point (update or delete from jintour properties table)
		$componentArgs = array('property_uid' => $castor_properties->propertys_uid);
		$MiniComponents->triggerEvent('04902', $componentArgs);

		$webhook_notification						   	= new stdClass();
		$webhook_notification->webhook_event			= 'property_state_change';
		$webhook_notification->webhook_event_description= 'A catchall webhook notification which notes that the property state has changed. Primarily designed for caching features to remove/refresh cache elements';
		$webhook_notification->data					 	= new stdClass();
		$webhook_notification->data->property_uid	   	=  $castor_properties->propertys_uid;
		add_webhook_notification($webhook_notification);

		//redirect back to edit property page
		castorRedirect(castorUrl(CASTOR_SITEPAGE_URL.'&task=edit_property'));
	}

	public function encode_lessgreaterthans($string)
	{
		$string = str_replace('<', '&#60;', $string);
		$string = str_replace('>', '&#62;', $string);

		return $string;
	}

	public function convert_lessgreaterthans($string)
	{
		$string = str_replace('&#60;', '<', $string);
		$string = str_replace('&#62;', '>', $string);

		return $string;
	}


	public function getRetVals()
	{
		return null;
	}
}

