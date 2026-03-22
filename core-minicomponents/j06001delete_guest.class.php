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

class j06001delete_guest
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
		$id = castorGetParam($_REQUEST, 'id', 0);
		$defaultProperty = getDefaultProperty();
		
		jr_import('jrportal_guests');
		$jrportal_guests = new jrportal_guests();
		$jrportal_guests->id = $id;
		$jrportal_guests->property_uid = $defaultProperty;
		
		if ($jrportal_guests->delete_guest()) {
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=list_guests'), jr_gettext('_CASTOR_FRONT_DELETEGUEST_GUESTDELETED', '_CASTOR_FRONT_DELETEGUEST_GUESTDELETED', false));
		} else {
			echo jr_gettext('_CASTOR_FRONT_DELETEGUEST_UNABLETODELETEGUEST', '_CASTOR_FRONT_DELETEGUEST_UNABLETODELETEGUEST', false);
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

