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
	
	/**
	 * @package Castor\Core\Minicomponents
	 *
	 * Loads the thisJRUser object, which is used throughout the system to make decisions based on who the current user is and their status within the system.
	 *
	 */

class j00002usermanagement
{
	#[AllowDynamicProperties]
	/**
	 *
	 * Constructor
	 *
	 * Main functionality of the Minicomponent
	 *
	 *
	 *
	 */
	 
	public function __construct($componentArgs = null)
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}

		//jr_user is not ready yet
		set_showtime('jr_user_ready', false);

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');

		if ($thisJRUser->userIsManager && !castor_cmsspecific_areweinadminarea()) {
			$thisJRUser->check_currentproperty();
		}

		$thisProperty = intval(castorGetParam($_REQUEST, 'thisProperty', 0));

		if ($thisProperty > 0 && $thisJRUser->userIsManager && in_array($thisProperty, $thisJRUser->authorisedProperties) && $thisProperty != $thisJRUser->currentproperty) {
			$thisJRUser->set_currentproperty($thisProperty);
		}

		if ($thisJRUser->currentproperty == 0 && $thisJRUser->userIsManager) {
			$thisJRUser->setToAnyAuthorisedProperty();
		}

		//TODO: may not be needed
		$this->userObject = $thisJRUser;

		//jr_user is now ready
		set_showtime('jr_user_ready', true);

		//partners TODO: move to jr_user class as new access level
		if ($thisJRUser->id > 0 && !castor_cmsspecific_areweinadminarea()) {
			jr_import('castor_partners');
			$partners = new castor_partners();
			$thisJRUser->is_partner = $partners->is_this_cms_user_a_partner($thisJRUser->id);
		}
	}


	public function getRetVals()
	{
		return $this->userObject;
	}
}

