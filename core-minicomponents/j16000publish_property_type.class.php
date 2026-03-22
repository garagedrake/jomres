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

class j16000publish_property_type
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

		$id = castorGetParam($_GET, 'id', 0);

		if ($id == 0) {
			return;
		}

		$castor_property_types = castor_singleton_abstract::getInstance('castor_property_types');
		$success = $castor_property_types->publish_property_type($id);
		


		if ($success) {
			$save_message = jr_gettext('_CASTOR_COM_PTYPES_SAVED', '_CASTOR_COM_PTYPES_SAVED', false);
			$message_class = '';
		} else {
				$halting_properties = jr_gettext('_CASTOR_COM_PTYPES_NOT_UNPUBLISHED', '_CASTOR_COM_PTYPES_NOT_UNPUBLISHED', false);
			foreach ($castor_property_types->properties_that_prevent_property_type_from_being_unpublished as $property_uid) {
				$halting_properties .= $property_uid." ";
			}
			$save_message = $halting_properties;
			$message_class = 'alert-danger';
		}

		castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=list_property_types'), $save_message, $message_class);
	}


	public function getRetVals()
	{
		return null;
	}
}

