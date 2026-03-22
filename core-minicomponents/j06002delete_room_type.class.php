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

class j06002delete_room_type
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
		
		if ($room_classes_uid < 1) {
			throw new Exception('Room class ID not passed');
		}
		
		$castor_room_types = castor_singleton_abstract::getInstance('castor_room_types');
		$castor_room_types->get_all_room_types();
		
		$castor_room_types->validate_manager_access_to_room_type($room_classes_uid);
		
		
		$castor_room_types = castor_singleton_abstract::getInstance('castor_room_types');
		$success = $castor_room_types->delete_room_type(array($room_classes_uid));

		if ($success) {
			$save_message = jr_gettext('_CASTOR_COM_MR_ROOMCLASS_DELETED', '_CASTOR_COM_MR_ROOMCLASS_DELETED', false);
		} else {
			$save_message = 'Unable to delete room type. It may still be used by some properties.';
		}

		$webhook_notification						   	= new stdClass();
		$webhook_notification->webhook_event			= 'property_state_change';
		$webhook_notification->webhook_event_description= 'A catchall webhook notification which notes that the property state has changed. Primarily designed for caching features to remove/refresh cache elements';
		$webhook_notification->data					 	= new stdClass();
		$webhook_notification->data->property_uid	   	= $property_uid;
		add_webhook_notification($webhook_notification);

		castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=list_room_types'), jr_gettext('_CASTOR_COM_MR_VRCT_ROOMTYPES_SAVE_INSERT', '_CASTOR_COM_MR_VRCT_ROOMTYPES_SAVE_INSERT', false));
	}


	public function getRetVals()
	{
		return null;
	}
}

