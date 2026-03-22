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
	 * Sends the new property welcome email
	 *
	 */

class j04950translation_definitions_rooms
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
		$property_uid = getDefaultProperty();
		$mrConfig = getPropertySpecificSettings($property_uid);
		if ($mrConfig[ 'singleRoomProperty' ] == 1) {
			$this->retVals= [];
			return;
		}

		$basic_room_details = castor_singleton_abstract::getInstance('basic_room_details');
		$basic_room_details->get_all_rooms($property_uid);

		$definitions = array();
		$section_name = jr_gettext('_CASTOR_COM_MR_VRCT_TAB_ROOM', '_CASTOR_COM_MR_VRCT_TAB_ROOM', false);

		if (!empty($basic_room_details->rooms)) {
			foreach ($basic_room_details->rooms as $room) {
					$room_name =  $room["room_name"];
					$room_tagline =  $room["tagline"];
					$room_description =  $room["description"];
					$subtitle = jr_gettext('_CASTOR_COM_MR_VRCT_ROOM_HEADER_NUMBER', '_CASTOR_COM_MR_VRCT_ROOM_HEADER_NUMBER', false).' '.$room['room_number'];
					$definitions[$section_name][$subtitle][] = [
						'definition' => jr_gettext('_CASTOR_CUSTOMTEXT_ROOMNAME_TITLE'.$room['room_uid'], stripslashes($room_name)),
						'label' => '_CASTOR_COM_MR_EB_ROOM_NAME',
						'translate_label' => true
						];

					$definitions[$section_name][$subtitle][] = [
						'definition' => jr_gettext('_CASTOR_CUSTOMTEXT_ROOM_TAGLINE'.$room['room_uid'], $room_tagline),
						'label' => '_CASTOR_ROOM_TAGLINE',
						'translate_label' => true
						];

					$definitions[$section_name][$subtitle][] = [
						'definition' => jr_gettext('_CASTOR_CUSTOMTEXT_ROOM_DESCRIPTION_'.$room['room_uid'], $room_description),
						'label' => '_CASTOR_ROOM_DESCRIPTION',
						'translate_label' => true
						];
			}
		}


		$this->retVals = $definitions;
	}

	public function getRetVals()
	{
		return $this->retVals;
	}
}

