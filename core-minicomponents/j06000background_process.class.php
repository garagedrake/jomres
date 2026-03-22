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
	 * Called by the webhooks watcher that triggers background tasks for processing by webhook integrations
	 *
	 */

class j06000background_process
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;
			return;
		}

			
			$payload_source = castorGetParam($_REQUEST, 'payload_source', '');
			
			logging::log_message("Received deferred message notification ", 'message_handling', 'DEBUG');
			
		if ($payload_source != '') {
			jr_import('castor_deferred_tasks');
			$castor_deferred_tasks = new castor_deferred_tasks();
			$castor_deferred_tasks->handle_message($payload_source);
		}
	}




	function getRetVals()
	{
		return null;
	}
}

