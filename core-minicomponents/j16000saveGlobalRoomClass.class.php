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

class j16000saveGlobalRoomClass
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

		$castor_room_types = castor_singleton_abstract::getInstance('castor_room_types');

		$castor_room_types->room_type['room_classes_uid'] = (int) castorGetParam($_POST, 'roomClassUid', 0);
		$castor_room_types->room_type['room_class_abbv'] = castorGetParam($_POST, 'room_class_abbv', '');
		$castor_room_types->room_type['room_class_full_desc'] = castorGetParam($_POST, 'room_class_desc', '');
		$castor_room_types->room_type['ptype_xref'] = castorGetParam($_POST, 'ptype_ids', array());
		$castor_room_types->room_type['image'] = castorGetParam($_POST, 'image', '');
		$castor_room_types->room_type['property_uid'] = 0;

		$castor_room_types->save_room_type();

		castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=listGlobalroomTypes'), jr_gettext('_CASTOR_COM_MR_VRCT_ROOMTYPES_SAVE_INSERT', '_CASTOR_COM_MR_VRCT_ROOMTYPES_SAVE_INSERT', false));
	}


	public function getRetVals()
	{
		return null;
	}
}

