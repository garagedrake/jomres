<?php
/**
 * Core file
 *
 * @author Vince Wooll <sales@castor.net>
 *  @version Castor 10.7.2
 * @package Castor
 * @copyright	2005-2023 Vince Wooll
 * Castor (tm) PHP, CSS & Javascript files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly.
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

class j16000unsuspend_user
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
	 
	function __construct()
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}
		
		$cms_user_id = intval(castorGetParam($_REQUEST, 'cms_user_id', 0));
		
		if ($cms_user_id == 0) {
			return;
		}

		$castor_users = castor_singleton_abstract::getInstance('castor_users');
		
		if ($castor_users->get_user($cms_user_id)) {
			jr_import('castor_suspensions');
			$castor_suspensions = new castor_suspensions();
			$castor_suspensions->set_manager_id($cms_user_id);
			$castor_suspensions->unsuspend_manager();
			$castor_suspensions->publish_managers_properties();
		}

		castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN . "&task=list_users"), "");
	}


	function getRetVals()
	{
		return null;
	}
}

