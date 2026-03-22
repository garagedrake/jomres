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
	 * Media centre uses this to determine individual ids for uploading of room type images
	 *
	 */

class j03381room_types
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
		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		if (!$thisJRUser->userIsManager) {
			return;
		}
		
		$dropdown = '';
		
		$property_uid = getDefaultProperty();
		
		$castor_room_types = castor_singleton_abstract::getInstance('castor_room_types');
		$castor_room_types->get_all_room_types();

		if (isset($castor_room_types->property_specific_room_types[$property_uid])) {
			$room_types = array();
			
			foreach ($castor_room_types->property_specific_room_types[$property_uid] as $key => $rt) {
				$room_type = $rt['room_class_abbv'];
	
				$room_types[ ] = castorHTML::makeOption($key, $room_type);
			}

			$dropdown = castorHTML::selectList($room_types, 'resource_id', ' autocomplete="off" class="btn btn-success btn-lg" size="1" ', 'value', 'text', '', false);
		}

		$this->ret_vals = $dropdown;
	}


	/**
	 * @return string
	 */
	public function getRetVals()
	{
		return $this->ret_vals;
	}
}

