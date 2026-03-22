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

class j16000save_property_type
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

		$castor_property_types = castor_singleton_abstract::getInstance('castor_property_types');

		$castor_property_types->property_type = array();
		$castor_property_types->property_type['id'] = (int) castorGetParam($_POST, 'id', 0);
		$castor_property_types->property_type['ptype'] = castorGetParam($_POST, 'ptype', '');
		$castor_property_types->property_type['ptype_desc'] = strtolower(castorGetParam($_POST, 'ptype_desc', ''));
		$castor_property_types->property_type['ptype_desc'] = preg_replace('/[^A-Za-z0-9_-]+/', '', $castor_property_types->property_type['ptype_desc']);
		$castor_property_types->property_type['mrp_srp_flag'] = (int) castorGetParam($_POST, 'mrp_srp_flag', 0);
		$castor_property_types->property_type['marker'] = castorGetParam($_POST, 'marker', '');
		$castor_property_types->property_type['has_stars'] = (int)castorGetParam($_POST, 'has_stars', '');

		$castor_property_types->save_property_type();

		castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=list_property_types'), jr_gettext('_CASTOR_COM_PTYPES_SAVED', '_CASTOR_COM_PTYPES_SAVED', false));
	}


	public function getRetVals()
	{
		return null;
	}
}

