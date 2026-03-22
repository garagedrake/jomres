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

class j06001dashboard_resources_ajax
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

		$property_uid = castorGetParam($_GET, 'property_uid', 0);
		if ($property_uid == 0) {
			$property_uid = getDefaultProperty();
		}

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		if (!in_array($property_uid, $thisJRUser->authorisedProperties)) {
			return;
		}

		$mrConfig = getPropertySpecificSettings($property_uid);
		if ($mrConfig[ 'is_real_estate_listing' ] == 1) {
			return;
		}

		$current_property_details = castor_singleton_abstract::getInstance('basic_property_details');
		$current_property_details->gather_data($property_uid);

		$basic_room_details = castor_singleton_abstract::getInstance('basic_room_details');
		$basic_room_details->get_all_rooms($property_uid);

		$rooms = array();

		foreach ($basic_room_details->rooms as $r) {
			$name = '';
			if ($mrConfig[ 'singleRoomProperty' ] == 0) {
				if ($r['room_number'] != '') {
					$name .= $r['room_number'].' - ';
				}
				if ($r['room_name'] != '') {
					$name .= $r['room_name'].' - ';
				}
			}
			if (!isset($current_property_details->all_room_types[ $r['room_classes_uid'] ][ 'room_class_abbv' ])) {
				$current_property_details->all_room_types[ $r['room_classes_uid'] ][ 'room_class_abbv' ] = "";
			}
			$name .= $current_property_details->all_room_types[ $r['room_classes_uid'] ][ 'room_class_abbv' ];
			$siteConfig		= castor_singleton_abstract::getInstance('castor_config_site_singleton');
			$jrConfig		  = $siteConfig->get();
			if ($jrConfig['development_production'] == 'development') {
				$name .= " UID ".$r['room_uid'];
			}
			
			$rooms[] = array(
							'id' => $r['room_uid'],
							'title' => $name,
							'itemtype' => $current_property_details->all_room_types[ $r['room_classes_uid'] ][ 'room_class_abbv' ],
							);
		}

		echo json_encode($rooms);
		exit;
	}

	public function getRetVals()
	{
		return null;
	}
}

