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
	 * Builds the cancellation email that is sent to guests
	 *
	 */

class j03150email_guest_cancelbooking
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

		$default_template = CASTOR_TEMPLATEPATH_BACKEND.JRDS.'email_guest_cancelbooking.html';

		$this->ret_vals = array('type' => 'email_guest_cancelbooking', 'name' => jr_gettext('_CASTOR_GUEST_CANCELBOOKING_EMAILNAME', '_CASTOR_GUEST_CANCELBOOKING_EMAILNAME', false), 'desc' => jr_gettext('_CASTOR_GUEST_CANCELBOOKING_EMAILDESC', '_CASTOR_GUEST_CANCELBOOKING_EMAILDESC', false), 'default_template' => $default_template);
	}


	/**
	 * @return array
	 */
	public function getRetVals()
	{
		return $this->ret_vals;
	}
}

