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

class j16000rerun_plugin_installer_script
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
		$plugin = castorGetParam($_REQUEST, 'plugin', '');
		
		foreach ($MiniComponents->registeredClasses as $trigger => $plugins) {
			foreach ($plugins as $plugin_name => $plugin_info) {
				if ($plugin_name == $plugin) {
					$path = $MiniComponents->registeredClasses[$trigger][$plugin_name]['filepath'];
					if (file_exists($path."plugin_install.php")) {
						define("CASTOR_INSTALLER", 1);
						require($path."plugin_install.php");
						castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=showplugins'), 'Install script has been re-run');
					} else {
						echo "Oops, the file ".$path."plugin_install.php"." does not exist";
					}
				}
			}
		}
		
/* 		if (file_exists()) {

		} */

		castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=showplugins'), '');
	}


	public function getRetVals()
	{
		return null;
	}
}

