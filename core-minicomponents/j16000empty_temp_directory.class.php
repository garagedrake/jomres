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

class j16000empty_temp_directory
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
	 
	public function __construct($componentArgs)
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}


		$this->filesystem = castor_singleton_abstract::getInstance('castor_filesystem', CASTORCONFIG_ABSOLUTE_PATH)->getFilesystem();
	
		if ($this->filesystem->deleteDir('local://'.str_replace(CASTORCONFIG_ABSOLUTE_PATH, '', CASTOR_TEMP_ABSPATH))) {
			if ($this->filesystem->createDir('local://'.str_replace(CASTORCONFIG_ABSOLUTE_PATH, '', CASTOR_TEMP_ABSPATH))) {
				echo jr_gettext("EMPTY_TEMP_DIR_DONE", "EMPTY_TEMP_DIR_DONE", false);
			}
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

