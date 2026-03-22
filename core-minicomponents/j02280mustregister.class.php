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
	 * Page that outputs the message that users must register before they can perform a booking
	 */

class j02280mustregister
{

	/**
	 *
	 * Constructor
	 *
	 * Main functionality of the Minicomponent
	 *
	 * Outputs a message that the user must be logged in before they can perform an action, and provides a link to the registration page.
	 *
	 */
	 
	public function __construct()
	{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
		$MiniComponents = castor_singleton_abstract::getInstance('mcHandler');
		if ($MiniComponents->template_touch) {
			$this->template_touchable = true;

			return;
		}
		
		echo '<a href="'.castor_cmsspecific_getregistrationlink().'">'.jr_gettext('_CASTOR_REGISTEREDUSERSONLYBOOK', '_CASTOR_REGISTEREDUSERSONLYBOOK', false).'</a>';
	}



	/**
	 * @return null
	 */
	public function getRetVals()
	{
		return null;
	}
}

