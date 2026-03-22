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

class j16000delete_obsolete_files
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
		
		jr_import('castor_obsolete_file_handling');
		$obsolete_files = new castor_obsolete_file_handling();

		if ($obsolete_files->remove_obsolete_files()) {
			castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=obsolete_files_check'), '');
		} else {
			echo 'Could not delete obsolete files, please do ti manually.';
		}
	}

   //Must be included in every mini-component.
	public function getRetVals()
	{
		return null;
	}
}

