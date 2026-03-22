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
	 * After booking creation, triggers the 07005 and 07010 minicomponents
	 *
	 */

class j03200jrportal
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

		if ($MiniComponents->eventFileExistsCheck('07005')) {
			$propertys_uids = $MiniComponents->triggerEvent('07005');
		} // Optional minicomponent trigger, eg for system cleanups or other pre-booking activity

		if ($MiniComponents->eventFileExistsCheck('07010')) {
			$MiniComponents->triggerEvent('07010', $componentArgs);
		} // Allows us to run post insertion functionality for importing into foreign systems. Currently used for inserting commission line items
	}


	/**
	 * @return null
	 */
	public function getRetVals()
	{
		return null;
	}
}

