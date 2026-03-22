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

class j06005expire_tokens
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
	 
	function __construct($componentArgs)
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;
			return;
		}

		$ePointFilepath=get_showtime('ePointFilepath');
		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');

		$client_id			= castorGetParam($_REQUEST, 'client_id', "");

		if ($client_id == "") {
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL . "&task=oauth"), "");
		}

		$thisJRUser = castor_singleton_abstract::getInstance('jr_user');
		$query = "SELECT client_id FROM #__castor_oauth_clients WHERE client_id = '".$client_id."' AND `user_id` = ".(int)$thisJRUser->id . ' ';
		$result = doSelectSql($query);

		if (count($result)==1) {
			$expires = date('Y-m-d H:i:s', strtotime('now'));
			$query = "UPDATE #__castor_oauth_access_tokens SET `expires` = '".$expires."'
				WHERE `client_id`= '".$client_id."' AND `user_id` = ".(int)$thisJRUser->id."";
			doInsertSql($query);
		}

		castorRedirect(castorURL(CASTOR_SITEPAGE_URL . "&task=oauth_edit_client&client_id=".$client_id), "");
	}

	/**
	#
	 * Must be included in every mini-component
	#
	 * Returns any settings that the mini-component wants to send back to the calling script. In addition to being returned to the calling script they are put into an array in the mcHandler object as eg. $mcHandler->miniComponentData[$ePoint][$eName]
	#
	 */

	function getRetVals()
	{
		return null;
	}
}

