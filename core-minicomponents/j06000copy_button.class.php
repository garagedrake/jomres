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
	 * Redirects the user to the CMS's login page
	 *
	 */

class j06000copy_button
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
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = false;
			return;
		}
		
		if (isset($_REQUEST['copy_button_id'])) {
			$button_id = castorGetParam($_REQUEST, 'copy_button_id', '');
			echo simple_template_output(CASTOR_TEMPLATEPATH_FRONTEND, 'copy_button.html', $_REQUEST['copy_button_id']);
		}
	}


	public function getRetVals()
	{
		return null;
	}
}

