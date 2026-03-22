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

class j06002save_resource
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
		
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		
		jr_import('jrportal_rooms');
		$jrportal_rooms = new jrportal_rooms();

		$jrportal_rooms->propertys_uid				= $defaultProperty;
		$jrportal_rooms->room_uid					= (int) castorGetParam($_POST, 'roomUid', 0);
		$jrportal_rooms->room_classes_uid			= (int) castorGetParam($_POST, 'roomClasses', 0);
		 $jrportal_rooms->max_people					= (int) castorGetParam($_POST, 'max_people', 0);
		$jrportal_rooms->max_adults					= (int) castorGetParam($_POST, 'max_adults', 0);
		$jrportal_rooms->max_children				= (int) castorGetParam($_POST, 'max_children', 0);

		if ($mrConfig[ 'singleRoomProperty' ] == '1') { // It's an SRP
			$jrportal_rooms->max_adults = (int) castorGetParam($_POST, 'max_people', 0);
			$jrportal_rooms->max_children				= 0;
		}

		$jrportal_rooms->room_name					= getEscaped(castorGetParam($_POST, 'room_name', ''));
		$jrportal_rooms->room_number				= getEscaped(castorGetParam($_POST, 'room_number', ''));
		$jrportal_rooms->room_floor					= getEscaped(castorGetParam($_POST, 'room_floor', ''));
		$jrportal_rooms->singleperson_suppliment	= (float) castorGetParam($_POST, 'singleperson_suppliment', 0.0);
		$jrportal_rooms->room_features_uid			= castorGetParam($_POST, 'features_list', array());
		$jrportal_rooms->tagline					= getEscaped(castorGetParam($_POST, 'room_tagline', ''));
		$jrportal_rooms->surcharge					= (float) castorGetParam($_POST, 'surcharge', 0.0);
		
		//html editor fields
		if ($jrConfig[ 'allowHTMLeditor' ] == '0') {
			$jrportal_rooms->description			= $this->convert_greaterthans(castorGetParam($_POST, 'room_description', ''));
			$jrportal_rooms->description			= strip_tags($jrportal_rooms->description);
		} else {
			$jrportal_rooms->description			= castorGetParam($_POST, 'room_description', '');
		}

		if ($jrportal_rooms->room_uid > 0) {
			$jrportal_rooms->commit_update_room();
		} else {
			$jrportal_rooms->commit_new_room();
		}

		$the_correct_room_type_id = $jrportal_rooms->room_classes_uid; // This value will be reset when we make a new instance of jrportal_rooms

		if ($mrConfig[ 'singleRoomProperty' ] == '1') {
			$jrportal_rooms = new jrportal_rooms();

			$basic_room_details = castor_singleton_abstract::getInstance('basic_room_details');
			$basic_room_details->get_all_rooms($defaultProperty);

			jr_import('castor_occupancy_levels');
			$castor_occupancy_levels = new castor_occupancy_levels($defaultProperty);
			foreach ($castor_occupancy_levels->occupancy_levels as $key => $val) {
				if ($key != $the_correct_room_type_id) {
					unset($castor_occupancy_levels->occupancy_levels[$key]);
				}
			}

			$castor_occupancy_levels->set_occupancy_level($the_correct_room_type_id,(int) castorGetParam($_POST, 'max_people', 0),  0,  (int) castorGetParam($_POST, 'max_people', 0) );
			$castor_occupancy_levels->save_occupancy_levels($the_correct_room_type_id);
		}

		jr_import('castor_calculate_accommodates_value');
		$castor_calculate_accommodates_value = new castor_calculate_accommodates_value($defaultProperty);
		$castor_calculate_accommodates_value->calculate_accommodates_value();

		$webhook_notification						   	= new stdClass();
		$webhook_notification->webhook_event			= 'property_state_change';
		$webhook_notification->webhook_event_description= 'A catchall webhook notification which notes that the property state has changed. Primarily designed for caching features to remove/refresh cache elements';
		$webhook_notification->data					 	= new stdClass();
		$webhook_notification->data->property_uid	   	=  $defaultProperty;
		add_webhook_notification($webhook_notification);

		if ($mrConfig[ 'singleRoomProperty' ] == '1') {
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=list_occupancy_levels'), '');
		} else {
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=list_resources'), '');
		}
	}

	public function convert_greaterthans($string)
	{
		$string = str_replace('&#38;gt;', '>', $string);

		return $string;
	}
	

	public function getRetVals()
	{
		return null;
	}
}

