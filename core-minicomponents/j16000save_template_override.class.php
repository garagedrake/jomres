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

class j16000save_template_override
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;

			return;
		}
		
		$template_overrides = castor_singleton_abstract::getInstance('template_overrides');
		
		$template_name			= (string) castorGetParam($_POST, 'template_name', '');
		$template_path			= (string) castorGetParam($_POST, 'template_path', '');

		// Older template override plugins had templates in the template root. We are extending here to allow copies of the files to exist in bootstrap specific version directories.
		if (!file_exists($template_path.$template_name)) {
			$bs_version = castor_bootstrap_version();
			if (file_exists(CASTORPATH_BASE.$template_path."templates".JRDS."bootstrap".$bs_version.JRDS.$template_name)) {
				$template_path = $template_path."templates".JRDS."bootstrap".$bs_version.JRDS;
			}
		}

		$template_overrides->template_overrides[$template_name]['template_name']	= $template_name;
		$template_overrides->template_overrides[$template_name]['path']				= addslashes($template_path);

		$template_overrides->save_template_override($template_name);
		
		castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=list_template_overrides'), '');
	}


	public function getRetVals()
	{
		return null;
	}
}

