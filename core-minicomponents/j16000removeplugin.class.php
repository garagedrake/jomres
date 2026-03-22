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

class j16000removeplugin
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
		$debugging = false;
		$pluginName = castorGetParam($_REQUEST, 'plugin', '');
		if ($pluginName == 'subsc<x>riptions') {
			$pluginName = 'subscriptions';
		}
		if (!dropPlugin($pluginName)) {
			echo 'Plugin could not be removed';
		}

		$registry = castor_singleton_abstract::getInstance('minicomponent_registry');
		unlink($registry->registry_file);
		unlink(CASTOR_TEMP_ABSPATH.'registry_classes.php');

		if (!$debugging) {
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=showplugins#'.$pluginName));
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

