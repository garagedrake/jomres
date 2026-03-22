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

class j06002editinplace
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
		if (!$thisJRUser->userIsManager) {
			return;
		}
		$siteConfig = castor_singleton_abstract::getInstance('castor_config_site_singleton');
		$jrConfig = $siteConfig->get();
		$property_uid = (int) getDefaultProperty();

		if ($jrConfig[ 'allowHTMLeditor' ] == '1') {
			$customText = castorGetParam($_POST, 'value', '');
		} else {
			$customText = castorGetParam($_POST, 'value', '');
		}

		$theConstant = filter_var($_POST[ 'pk' ], FILTER_SANITIZE_SPECIAL_CHARS);

		$castor_target_language = get_showtime('lang');
		if (isset($_POST[ 'castor_target_language' ])) {
			$castor_target_language = castorGetParam($_POST, 'castor_target_language', '');
		}
		$theConstant = filter_var($_POST[ 'pk' ], FILTER_SANITIZE_SPECIAL_CHARS);

		$result = updateCustomText($theConstant, $customText, true, $property_uid, 0, $castor_target_language);
		//$result = false;
		if ($result) {
			header('Status: 200');
			echo castor_decode($customText);
		} else {
			header('Status: 500');
			echo 'Something burped';
		}

		$webhook_notification						   	= new stdClass();
		$webhook_notification->webhook_event			= 'property_state_change';
		$webhook_notification->webhook_event_description= 'A catchall webhook notification which notes that the property state has changed. Primarily designed for caching features to remove/refresh cache elements';
		$webhook_notification->data					 	= new stdClass();
		$webhook_notification->data->property_uid	   	=  $property_uid;
		add_webhook_notification($webhook_notification);

	}


	public function getRetVals()
	{
		return null;
	}
}

