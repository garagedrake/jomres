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

class j06000confirmbooking
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
		
		$castor_gdpr_optin_consent = new castor_gdpr_optin_consent();
		if (!$castor_gdpr_optin_consent->user_consents_to_storage()&& !isset($_REQUEST['skip_consent_form'])) {
			echo $consent_form = $MiniComponents->specificEvent('06000', 'show_consent_form', array ('output_now' => false));
			return;
		}

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		
		$mrConfig = getPropertySpecificSettings();

		if ($thisJRUser->userIsManager) {
			$MiniComponents->triggerEvent('02990');
		} // Trigger the booking confirmation page
		else {
			if (($mrConfig[ 'visitorscanbookonline' ] == '1') && (!$thisJRUser->userIsManager)) {
				if (!$thisJRUser->userIsRegistered && $mrConfig[ 'registeredUsersOnlyCanBook' ] == '1') {
					$MiniComponents->triggerEvent('02280');
				} else {
					$MiniComponents->triggerEvent('02990');
				} // Trigger the booking confirmation page
			}
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

