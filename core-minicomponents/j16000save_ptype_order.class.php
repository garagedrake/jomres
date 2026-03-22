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

class j16000save_ptype_order
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
		$order_array = castorGetParam($_REQUEST, 'order_array', array());

		foreach ($order_array as $ptype_id => $order) {
			$query = "UPDATE #__castor_ptypes SET `order`='".$order."' WHERE id='".(int) $ptype_id."'";
			doInsertSql($query, '');
		}
		castorRedirect(castorURL(CASTOR_SITEPAGE_URL_ADMIN.'&task=listPropertyTypes'), '');
	}


	public function getRetVals()
	{
		return null;
	}
}

