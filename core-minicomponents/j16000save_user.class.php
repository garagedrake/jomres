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

class j16000save_user
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
		
		$id				= (int)castorGetParam($_POST, 'id', 0);
		$cms_user_id 	= (int)castorGetParam($_POST, 'cms_user_id', 0);
		
		$castor_users = castor_singleton_abstract::getInstance('castor_users');
		
		if ($id > 0 && $cms_user_id > 0) {
			$castor_users->get_user($cms_user_id);
		}

		$castor_users->cms_user_id				= $cms_user_id;
		$castor_users->apikey 					= castorGetParam($_POST, 'apikey', '');
		$castor_users->authorised_properties 	= castorGetParam($_POST, 'authorised_properties', array());
		$castor_users->access_level 			= (int)castorGetParam($_POST, 'access_level', 0);
		
		//some checks
		if (empty($castor_users->authorised_properties) && $castor_users->access_level < 90) {
			echo "Error, you need to assign at least one property to this user";
			return;
		}
		
		if ($castor_users->id == 0) {
			$castor_users->commit_new_user();
		} else {
			$castor_users->commit_update_user();
		}

		castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN . "&task=list_users"), jr_gettext("_CASTOR_COM_MR_ASSIGNUSER_USERMODIFIEDMESAGE", '_CASTOR_COM_MR_ASSIGNUSER_USERMODIFIEDMESAGE', false));
	}


	function getRetVals()
	{
		return null;
	}
}

