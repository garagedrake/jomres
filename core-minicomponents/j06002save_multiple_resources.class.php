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

class j06002save_multiple_resources
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

		$defaultProperty = getDefaultProperty();

		$mrConfig = getPropertySpecificSettings();

		jr_import('jrportal_rooms');
		$jrportal_rooms = new jrportal_rooms();

		$jrportal_rooms->rooms_generator['propertys_uid'] = (int) $defaultProperty;
		$jrportal_rooms->rooms_generator['number_of_rooms'] = (int) castorGetParam($_POST, 'numberOfResources', 0);
		$jrportal_rooms->rooms_generator['room_classes_uid'] = (int) castorGetParam($_POST, 'resourcesType', 0);
		$jrportal_rooms->rooms_generator['max_people'] = (int) castorGetParam($_POST, 'maxGuests', 0);
		$jrportal_rooms->rooms_generator['delete_existing_rooms'] = (bool) castorGetParam($_POST, 'deleteExistingResources', false);

		$jrportal_rooms->commit_new_rooms();

		$webhook_notification						   	= new stdClass();
		$webhook_notification->webhook_event			= 'property_state_change';
		$webhook_notification->webhook_event_description= 'A catchall webhook notification which notes that the property state has changed. Primarily designed for caching features to remove/refresh cache elements';
		$webhook_notification->data					 	= new stdClass();
		$webhook_notification->data->property_uid	   	= $defaultProperty;
		add_webhook_notification($webhook_notification);

		castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=list_resources'), '');
	}


	public function getRetVals()
	{
		return null;
	}
}

