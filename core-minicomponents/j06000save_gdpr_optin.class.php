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

class j06000save_gdpr_optin
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
		$MiniComponents = castor_getSingleton('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}

		$optin = (bool)castorGetParam($_REQUEST, 'optin', 0);
		$return_url = castorGetParam($_REQUEST, 'return_url', 0);
		
		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		
		$castor_gdpr_optin_consent = new castor_gdpr_optin_consent();
		$castor_gdpr_optin_consent->optedin = $optin;
		$castor_gdpr_optin_consent->set_user_id($thisJRUser->id);
		$castor_gdpr_optin_consent->save_record();
		
		

		castorRedirect($return_url);
	}


	public function getRetVals()
	{
		return null;
	}
}

