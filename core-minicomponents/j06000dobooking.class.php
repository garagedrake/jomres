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

class j06000dobooking
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
		
		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		
		$mrConfig = getPropertySpecificSettings();

		if ($mrConfig[ 'visitorscanbookonline' ] == '0' && $thisJRUser->userIsManager != true) {
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL.'&task=contactowner&amp;selectedProperty='.get_showtime('property_uid')));
		}

		if ($thisJRUser->userIsManager) {
			$MiniComponents->triggerEvent('05020');
		} else {
			if (($mrConfig[ 'visitorscanbookonline' ] == '1') && (!$thisJRUser->userIsManager)) {
				if (!$thisJRUser->userIsRegistered && $mrConfig[ 'registeredUsersOnlyCanBook' ] == '1') {
					$MiniComponents->triggerEvent('02280');
				} else {
					$MiniComponents->triggerEvent('05020');
				}
			} else {
				$MiniComponents->specificEvent('00600', 'contactowner');
			} // Alternative if online bookings by guests is disabled
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

