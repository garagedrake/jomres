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

class j06002save_room_type
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}

		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		if ($jrConfig[ 'frontend_room_type_editing_allowed' ] == 0) {
			return;
		}
		
		$property_uid = getDefaultProperty();
		
		$room_classes_uid = intval(castorGetParam($_REQUEST, 'room_classes_uid', 0));

		$castor_room_types = castor_singleton_abstract::getInstance('castor_room_types');
		$castor_room_types->get_all_room_types();
		

		$feedback_message = jr_gettext('_CASTOR_COM_MR_VRCT_ROOMTYPES_SAVE_INSERT', '_CASTOR_COM_MR_VRCT_ROOMTYPES_SAVE_INSERT', false);
		
		if ($room_classes_uid > 0) {
			$castor_room_types->validate_manager_access_to_room_type($room_classes_uid);
			$feedback_message = jr_gettext('_CASTOR_COM_MR_VRCT_ROOMTYPES_SAVE_UPDATE', '_CASTOR_COM_MR_VRCT_ROOMTYPES_SAVE_UPDATE', false);
		}
		
		$basic_property_details = castor_singleton_abstract::getInstance('basic_property_details');
		$basic_property_details->gather_data($property_uid);

		$castor_room_types->room_type['room_classes_uid'] = (int)$room_classes_uid;
		$castor_room_types->room_type['property_uid'] = (int) $property_uid;
		$castor_room_types->room_type['room_class_abbv'] = castorGetParam($_POST, 'room_class_abbv', '');
		$castor_room_types->room_type['room_class_full_desc'] = castorGetParam($_POST, 'room_class_desc', '');
		$castor_room_types->room_type['ptype_xref'] = array ( "0" => $basic_property_details->multi_query_result[$property_uid]['ptype_id']) ;
		$castor_room_types->room_type['image'] = castorGetParam($_POST, 'image', '');

		$castor_room_types->save_room_type();

		$webhook_notification						   	= new stdClass();
		$webhook_notification->webhook_event			= 'property_state_change';
		$webhook_notification->webhook_event_description= 'A catchall webhook notification which notes that the property state has changed. Primarily designed for caching features to remove/refresh cache elements';
		$webhook_notification->data					 	= new stdClass();
		$webhook_notification->data->property_uid	   	=  $property_uid;
		add_webhook_notification($webhook_notification);

		castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=list_room_types'), $feedback_message);
	}


	public function getRetVals()
	{
		return null;
	}
}

